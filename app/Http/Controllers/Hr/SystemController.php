<?php

namespace App\Http\Controllers\Hr;

use App\Models\Admin;
use App\Models\AuditRecord;
use App\Models\CustomerMaster;
use App\Models\DesignationMaster;
use App\Models\District;
use App\Models\EmployeeMaster;
use App\Models\Hr\AuditLog;
use App\Models\Hr\Department as HrDepartment;
use App\Models\Hr\Designation as HrDesignation;
use App\Models\Hr\Employee;
use App\Models\Hr\LeaveType;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\User;
use App\Models\ProductMaster;
use App\Models\State;
use App\Models\StoreMaster;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

/**
 * System & Access — the audit trail for the whole application.
 *
 * Entries come from two tables and are merged into one timeline:
 *   spc_hr.audit_logs  (HR models + payroll / settings)
 *   spc.audit_records  (sales / admin models)
 * See App\Services\AuditTrail and App\Models\Concerns\Auditable.
 */
class SystemController extends Controller
{
    private const PER_PAGE = 20;

    private const ROLE_LABELS = [
        'employee' => 'Employee',
        'manager' => 'Reporting Manager',
        'hr_admin' => 'HR Admin',
        'super_admin' => 'Super Admin',
    ];

    /** Sales-order fields worth showing in the plain-language summary. */
    private const ORDER_FIELDS = [
        'd_date', 'c_order_status', 'payment_status', 'delivery_status', 'c_mode_of_payment',
        'c_transaction_id', 'invoice_no', 'order_type', 'c_customer_type', 'n_customer_id',
        'farm_care_advisor_id', 'nearest_franchise_id', 'n_total_sales_amount', 'n_total_gst',
        'n_total_discount', 'n_net_sales_amount',
    ];

    private array $cache = [];

    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('system');

        $areas = config('audit.areas', []);
        $area = $request->query('area');
        if ($area !== 'other' && ! isset($areas[$area])) {
            $area = null;
        }

        $page = max(1, (int) $request->query('auditPage', 1));
        $take = $page * self::PER_PAGE;

        $hrQuery = $this->scoped(AuditLog::query(), $area);
        $spcQuery = $this->scoped(AuditRecord::query(), $area);

        $total = (clone $hrQuery)->count() + (clone $spcQuery)->count();

        $hrRows = (clone $hrQuery)->with('user')->orderByDesc('created_at')->orderByDesc('id')->limit($take)->get();
        $spcRows = (clone $spcQuery)->orderByDesc('created_at')->orderByDesc('id')->limit($take)->get();

        $adminNames = Admin::whereIn('n_role_id', $spcRows->pluck('user_id')->filter()->unique())
            ->pluck('c_name', 'n_role_id');

        $rows = $hrRows->map(fn ($l) => $this->fromHr($l))
            ->concat($spcRows->map(fn ($r) => $this->fromSpc($r, $adminNames)))
            ->sortByDesc('ts')
            ->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)
            ->values();

        $rows->each(fn ($row) => $row->summary = $this->describe($row));

        $auditLog = new LengthAwarePaginator($rows, $total, self::PER_PAGE, $page, [
            'path' => $request->url(),
            'pageName' => 'auditPage',
        ]);
        $auditLog->withQueryString();

        [$moduleCounts, $totalAuditEntries] = $this->moduleCounts();
        $areaCounts = ['all' => $totalAuditEntries];
        $known = [];
        foreach ($areas as $key => $def) {
            $areaCounts[$key] = (int) collect($def['modules'])->sum(fn ($m) => $moduleCounts[$m] ?? 0);
            $known = array_merge($known, $def['modules']);
        }
        $areaCounts['other'] = (int) collect($moduleCounts)->except($known)->sum();

        $areaLabels = collect($areas)->map(fn ($d) => $d['label'])->all() + ['other' => 'Other'];

        return view('hr.modules.system', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'system',
            'auditLog' => $auditLog,
            'totalAuditEntries' => $totalAuditEntries,
            'todayCount' => AuditLog::where('created_at', '>=', now()->startOfDay())->count()
                + AuditRecord::where('created_at', '>=', now()->startOfDay())->count(),
            'areas' => $areaLabels,
            'areaCounts' => $areaCounts,
            'area' => $area,
        ]));
    }

    /* ------------------------------------------------------------------ */
    /*  Querying                                                           */
    /* ------------------------------------------------------------------ */

    /** Every module key the chips know about, including legacy names. */
    private function knownModules(): array
    {
        $modules = [];
        foreach (config('audit.areas', []) as $def) {
            $modules = array_merge($modules, $def['modules']);
        }

        return array_merge($modules, array_keys(config('audit.legacy_modules', [])));
    }

    private function scoped($query, ?string $area)
    {
        if ($area === null) {
            return $query;
        }
        if ($area === 'other') {
            return $query->whereNotIn('module', $this->knownModules());
        }

        $modules = config("audit.areas.$area.modules", []);
        foreach (config('audit.legacy_modules', []) as $legacy => $current) {
            if (in_array($current, $modules, true)) {
                $modules[] = $legacy;
            }
        }

        return $query->whereIn('module', $modules);
    }

    /** @return array{0: array<string,int>, 1: int} */
    private function moduleCounts(): array
    {
        $counts = [];
        $legacy = config('audit.legacy_modules', []);

        foreach ([AuditLog::class, AuditRecord::class] as $model) {
            $model::selectRaw('module, COUNT(*) as total')->groupBy('module')->pluck('total', 'module')
                ->each(function ($n, $m) use (&$counts, $legacy) {
                    $m = $legacy[$m] ?? $m;
                    $counts[$m] = ($counts[$m] ?? 0) + (int) $n;
                });
        }

        return [$counts, array_sum($counts)];
    }

    /* ------------------------------------------------------------------ */
    /*  Normalising the two tables into one row shape                      */
    /* ------------------------------------------------------------------ */

    private function fromHr(AuditLog $log): object
    {
        $new = $this->decode($log->new_value);
        $name = $log->user->name ?? ($new['_by'] ?? null);

        return (object) [
            'source' => 'hr',
            'module' => $log->module,
            'action' => strtoupper((string) $log->action),
            'record_id' => $log->record_id,
            'old' => $this->decode($log->old_value),
            'new' => $new,
            'old_value' => $log->old_value,
            'new_value' => $log->new_value,
            'ip_address' => $log->ip_address,
            'created_at' => $log->created_at,
            'user' => $name ? (object) ['name' => $name] : null,
            'ts' => $log->created_at ? Carbon::parse($log->created_at)->getTimestamp() : 0,
        ];
    }

    private function fromSpc(AuditRecord $rec, $adminNames): object
    {
        $action = strtoupper((string) $rec->action);
        $action = match ($action) {
            'CREATED' => 'CREATE',
            'UPDATED' => 'UPDATE',
            'DELETED' => 'DELETE',
            default => $action,
        };
        $name = $rec->user_id ? ($adminNames[$rec->user_id] ?? null) : null;
        $enc = fn ($v) => $v === null ? null : mb_substr(json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 0, 6000);

        return (object) [
            'source' => 'spc',
            'module' => config('audit.legacy_modules')[$rec->module] ?? $rec->module,
            'action' => $action,
            'record_id' => $rec->record_id,
            'old' => (array) ($rec->old_values ?? []),
            'new' => (array) ($rec->new_values ?? []),
            'old_value' => $enc($rec->old_values),
            'new_value' => $enc($rec->new_values),
            'ip_address' => $rec->ip_address,
            'created_at' => $rec->created_at,
            'user' => $name ? (object) ['name' => $name] : null,
            'ts' => $rec->created_at ? Carbon::parse($rec->created_at)->getTimestamp() : 0,
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Plain-language description of an audit entry                       */
    /* ------------------------------------------------------------------ */

    /** @return array{title:string, lines:array<int,string>, tone:string, icon:string} */
    private function describe(object $log): array
    {
        $old = $log->old;
        $new = $log->new;
        $action = $log->action;

        $tone = match (true) {
            str_contains($action, 'CREATE'), str_contains($action, 'RESTORE'), $action === 'PAYROLL_RUN' => 'add',
            str_contains($action, 'DELETE'), str_contains($action, 'DISCARD') => 'remove',
            default => 'edit',
        };

        $icon = match (true) {
            str_contains($action, 'RESTORE') => 'fa-rotate-left',
            $tone === 'add' => 'fa-plus',
            $tone === 'remove' => 'fa-trash-can',
            default => 'fa-pen',
        };

        // Entries written by the Auditable trait / AuditTrail::record() carry their own labels.
        $entity = $new['_entity'] ?? $old['_entity'] ?? null;
        if ($entity !== null) {
            return $this->describeGeneric($log, $entity, $new['_subject'] ?? $old['_subject'] ?? null, $tone, $icon);
        }

        $title = null;
        $lines = [];

        switch ($log->module) {
            case 'users':
                $name = ($log->record_id ? User::find($log->record_id)?->name : null) ?? ($new['name'] ?? 'a user');
                $icon = 'fa-user-gear';
                if ($action === 'CREATE') {
                    $title = 'Added user '.$name;
                    $lines = $this->diff([], $new, $log);
                } elseif ($action === 'UPDATE') {
                    $title = 'Changed access for '.$name;
                    $lines = $this->diff($old, $new, $log);
                }
                break;

            case 'payroll':
                if ($action === 'PAYROLL_RUN') {
                    $period = $new['period'] ?? $this->runLabel($log->record_id);
                    $title = 'Processed payroll for '.$period;
                    if (isset($new['employees'])) {
                        $lines[] = $new['employees'].' '.($new['employees'] == 1 ? 'employee' : 'employees')
                            .(isset($new['net']) ? ', net pay ₹'.number_format((float) $new['net'], 0) : '');
                    }
                    $icon = 'fa-indian-rupee-sign';
                } elseif ($action === 'PAYROLL_PAID') {
                    $title = 'Marked '.$this->runLabel($log->record_id).' payroll as paid';
                    $icon = 'fa-circle-check';
                    $tone = 'add';
                } elseif ($action === 'PAYROLL_DISCARD') {
                    $title = 'Discarded payroll for '.($old['period'] ?? $this->runLabel($log->record_id));
                    $icon = 'fa-trash-can';
                } elseif ($action === 'SALARY_UPDATE') {
                    $emp = $log->record_id ? Employee::with('user')->find($log->record_id) : null;
                    $title = 'Updated salary structure for '.($emp?->user?->name ?? 'an employee');
                    $icon = 'fa-file-invoice-dollar';
                    if (isset($new['gross_monthly'])) {
                        $before = isset($old['gross_monthly']) ? '₹'.number_format((float) $old['gross_monthly'], 0) : 'none';
                        $lines[] = 'Gross monthly: '.$before.' → ₹'.number_format((float) $new['gross_monthly'], 0);
                    }
                    if (! empty($new['effective_from'])) {
                        $lines[] = 'Starts from '.Carbon::parse($new['effective_from'])->format('d M Y');
                    }
                }
                break;

            case 'settings':
                $icon = 'fa-sliders';
                $leaveName = $new['leave_type'] ?? $old['leave_type'] ?? ($new['name'] ?? $old['name'] ?? null);
                if ($leaveName !== null) {
                    $title = match ($action) {
                        'CREATE' => 'Added leave type '.$leaveName,
                        'DELETE' => 'Deleted leave type '.$leaveName,
                        default => 'Changed leave entitlement for '.$leaveName,
                    };
                    $lines = $action === 'DELETE' ? $this->diff([], $old, $log) : ($action === 'CREATE' ? $this->diff([], $new, $log) : $this->diff($old, $new, $log));
                } elseif ($action === 'UPDATE') {
                    $key = array_key_first($new) ?? array_key_first($old);
                    if ($key !== null) {
                        $title = 'Changed setting: '.$this->label($key);
                        $lines[] = $this->value($key, $old[$key] ?? null, $log).' → '.$this->value($key, $new[$key] ?? null, $log);
                    }
                }
                break;
        }

        if ($title === null) {
            $label = config('audit.module_labels.'.$log->module, str_replace('_', ' ', (string) $log->module));
            $title = ucfirst(strtolower(str_replace('_', ' ', $action))).' in '.$label;
            $lines = $this->diff($old, $new, $log);
        }

        return ['title' => $title, 'lines' => $lines, 'tone' => $tone, 'icon' => $icon];
    }

    private function describeGeneric(object $log, string $entity, ?string $subject, string $tone, string $icon): array
    {
        $ent = preg_match('/^[A-Z]{2}/', $entity) ? $entity : lcfirst($entity);
        $name = $subject !== null && $subject !== '' ? ' '.$subject : '';
        $old = $log->old;
        $new = $log->new;

        $verb = match (true) {
            str_contains($log->action, 'CREATE') => 'Added',
            str_contains($log->action, 'DELETE') => 'Deleted',
            str_contains($log->action, 'RESTORE') => 'Restored',
            default => 'Updated',
        };

        $lines = [];
        if ($log->module === 'sales_orders' && isset($new['order_products']) && in_array($log->action, ['CREATE', 'UPDATE'], true)) {
            $lines = $this->orderLines($log, $old, $new);
        } elseif ($verb === 'Added') {
            $lines = $this->diff([], $new, $log, 6);
        } elseif ($verb === 'Updated') {
            $lines = $this->diff($old, $new, $log, 12);
        }

        return ['title' => $verb.' '.$ent.$name, 'lines' => $lines, 'tone' => $tone, 'icon' => $icon];
    }

    /** Readable summary of a sales-order save (fields + product lines). */
    private function orderLines(object $log, array $old, array $new): array
    {
        $lines = [];
        $creating = $log->action === 'CREATE';

        $customer = $new['customer']['c_customer_name'] ?? null;
        if ($creating) {
            if ($customer) {
                $lines[] = 'Customer: '.$customer;
            }
            $lines[] = 'Items: '.count($new['order_products']).', net amount ₹'.number_format((float) ($new['n_net_sales_amount'] ?? 0), 2);
            if (! empty($new['c_mode_of_payment'])) {
                $lines[] = 'Payment: '.$new['c_mode_of_payment'].(isset($new['payment_status']) ? ' ('.$new['payment_status'].')' : '');
            }

            return $lines;
        }

        $only = fn (array $a) => array_intersect_key($a, array_flip(self::ORDER_FIELDS));
        $lines = $this->diff($only($old), $only($new), $log, 8);

        if (isset($old['order_products'])) {
            $lines = array_merge($lines, $this->itemLines($old['order_products'], $new['order_products']));
        }

        if (isset($old['customer'], $new['customer'])) {
            foreach (['c_customer_name', 'n_mobile', 'c_address', 'c_pincode'] as $key) {
                if (($old['customer'][$key] ?? null) != ($new['customer'][$key] ?? null)) {
                    $lines[] = 'Customer '.strtolower($this->label($key)).': '
                        .$this->value($key, $old['customer'][$key] ?? null, $log).' → '.$this->value($key, $new['customer'][$key] ?? null, $log);
                }
            }
        }

        return $lines ?: ['Saved with no visible changes'];
    }

    private function itemLines(array $oldItems, array $newItems): array
    {
        $totals = function (array $items) {
            $out = [];
            foreach ($items as $i) {
                $id = $i['product_id'] ?? $i['n_id'] ?? null;
                if ($id !== null) {
                    $out[$id] = ($out[$id] ?? 0) + (int) ($i['qty'] ?? 0);
                }
            }

            return $out;
        };
        $before = $totals($oldItems);
        $after = $totals($newItems);

        $names = ProductMaster::withTrashed()->whereIn('n_product_id', array_unique(array_merge(array_keys($before), array_keys($after))))
            ->pluck('c_product_name', 'n_product_id');
        $nm = fn ($id) => $names[$id] ?? ('Product #'.$id);

        $lines = [];
        foreach ($after as $id => $qty) {
            if (! isset($before[$id])) {
                $lines[] = 'Added product: '.$nm($id).' × '.$qty;
            } elseif ($before[$id] !== $qty) {
                $lines[] = $nm($id).' quantity: '.$before[$id].' → '.$qty;
            }
        }
        foreach ($before as $id => $qty) {
            if (! isset($after[$id])) {
                $lines[] = 'Removed product: '.$nm($id);
            }
        }

        return $lines;
    }

    private function decode($json): array
    {
        $d = json_decode((string) $json, true);

        return is_array($d) ? $d : [];
    }

    private function runLabel($id): string
    {
        $run = $id ? PayrollRun::find($id) : null;

        return $run ? $run->monthLabel() : 'a month';
    }

    /** "Label: before → after" lines for every value that actually changed. */
    private function diff(array $old, array $new, object $log, int $limit = 0): array
    {
        $skip = ['leave_type', 'name', 'applied_to_existing', 'customer', 'order_products',
            'sales_order_id', 'n_sale_id', 'n_order_id', 'attendance_id', 'approved_by', 'n_created_by', 'created_by', 'updated_by'];
        $lines = [];
        $total = 0;

        foreach ($new as $key => $_) {
            if (str_starts_with((string) $key, '_') || in_array($key, $skip, true) || is_array($new[$key])) {
                continue;
            }
            // Don't list the record's own id when it was just created.
            if (! $old && $this->isOwnKey($key, $new[$key], $log)) {
                continue;
            }

            $before = $this->value($key, $old[$key] ?? null, $log);
            $after = $this->value($key, $new[$key] ?? null, $log);
            if ($old && $before === $after) {
                continue;
            }

            $total++;
            if ($limit && count($lines) >= $limit) {
                continue;
            }

            $lines[] = ($after === '[hidden]' || $before === '[hidden]')
                ? $this->label($key).': changed'
                : $this->label($key).': '.($old ? $before.' → '.$after : $after);
        }

        if ($limit && $total > count($lines)) {
            $lines[] = '+ '.($total - count($lines)).' more';
        }

        if (($new['applied_to_existing'] ?? false) === true) {
            $lines[] = 'Applied to every employee\'s current balance';
        }

        return $lines;
    }

    private function isOwnKey(string $key, $value, object $log): bool
    {
        if (! is_numeric($value) || (int) $value !== (int) $log->record_id) {
            return false;
        }

        return $key === 'id' || $key === 'n_sl_no' || (str_starts_with($key, 'n_') && str_ends_with($key, '_id'));
    }

    private function label(string $key): string
    {
        $special = [
            'is_active' => 'Status',
            'default_annual_days' => 'Days per year',
            'max_carry_forward' => 'Max days carried forward',
            'carry_forward' => 'Carry forward',
            'c_order_status' => 'Order status',
            'c_order_no' => 'Order no.',
            'd_date' => 'Order date',
            'c_mode_of_payment' => 'Payment mode',
            'c_transaction_id' => 'Transaction ID',
            'n_net_sales_amount' => 'Net amount',
            'n_total_sales_amount' => 'Gross amount',
            'n_total_gst' => 'GST',
            'n_total_discount' => 'Discount',
            'farm_care_advisor_id' => 'Farm care advisor',
            'nearest_franchise_id' => 'Franchise',
            'c_status' => 'Status',
            'c_store_status' => 'Status',
            'n_mobile' => 'Mobile',
            'n_whatsapp' => 'WhatsApp',
            'dob' => 'Date of birth',
            'ifsc' => 'IFSC',
        ];
        if (isset($special[$key])) {
            return $special[$key];
        }

        $base = preg_replace(['/^(n|c|d)_/', '/_id$/'], '', $key);
        $words = ['pf' => 'PF', 'esi' => 'ESI', 'pt' => 'Professional tax', 'tds' => 'TDS', 'wfh' => 'WFH', 'edli' => 'EDLI',
            'gst' => 'GST', 'hsn' => 'HSN', 'mrp' => 'MRP', 'uan' => 'UAN', 'kyc' => 'KYC', 'ip' => 'IP'];
        $parts = array_map(fn ($w) => $words[$w] ?? $w, explode('_', $base ?: $key));

        return ucfirst(implode(' ', $parts));
    }

    private function value(string $key, $v, object $log): string
    {
        if ($key === 'is_active') {
            return $v ? 'Active' : 'Suspended';
        }
        if ($key === 'role') {
            return self::ROLE_LABELS[$v] ?? ($v === null ? 'none' : (string) $v);
        }
        if (in_array($key, ['c_status', 'c_store_status'], true) && in_array($v, ['Y', 'N', 'y', 'n'], true)) {
            return strtoupper($v) === 'Y' ? 'Active' : 'Inactive';
        }
        if (is_bool($v) || in_array($key, ['carry_forward', 'applied_to_existing'], true)) {
            return $v ? 'Yes' : 'No';
        }
        if ($v === null || $v === '') {
            return 'empty';
        }
        if ($v === '[hidden]') {
            return '[hidden]';
        }
        if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $v)) {
            return Carbon::parse($v)->setTimezone(config('app.timezone'))->format('d M Y');
        }
        if (is_numeric($v) && ($resolved = $this->lookup($key, (int) $v, $log->source))) {
            return $resolved;
        }
        if (is_numeric($v)) {
            $n = rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.') ?: '0';

            return preg_match('/(amount|price|mrp|total|gst|discount|salary|gross|basic|net)$/', $key) ? '₹'.$n : $n;
        }

        return (string) $v;
    }

    /** Turn well-known foreign-key ids into names. */
    private function lookup(string $key, int $id, string $source): ?string
    {
        $resolver = match (true) {
            $key === 'department_id' => fn () => HrDepartment::find($id)?->name,
            $source === 'spc' && $key === 'n_designation_id' => fn () => DesignationMaster::where('n_designation_id', $id)->value('c_designation'),
            $source === 'spc' && $key === 'n_state_id' => fn () => State::where('n_state_id', $id)->value('name'),
            $source === 'spc' && $key === 'n_district_id' => fn () => District::where('id', $id)->value('district_name'),
            $source === 'spc' && in_array($key, ['n_store_id', 'nearest_franchise_id'], true) => fn () => StoreMaster::withTrashed()->where('n_store_id', $id)->value('c_store_name'),
            $source === 'spc' && $key === 'n_customer_id' => fn () => CustomerMaster::withTrashed()->where('n_customer_id', $id)->value('c_customer_name'),
            $source === 'spc' && in_array($key, ['n_employee_id', 'reporting_to', 'farm_care_advisor_id', 'n_fca_id'], true) => fn () => EmployeeMaster::withTrashed()->where('n_employee_id', $id)->value('c_employee_name'),
            $source === 'hr' && in_array($key, ['employee_id', 'reporting_manager_id'], true) => fn () => Employee::with('user')->find($id)?->user?->name,
            $source === 'hr' && $key === 'leave_type_id' => fn () => LeaveType::find($id)?->name,
            $source === 'hr' && $key === 'designation_id' => fn () => HrDesignation::find($id)?->title,
            default => null,
        };

        if ($resolver === null) {
            return null;
        }

        return $this->cache[$source.$key.$id] ??= ($resolver() ?: '');
    }
}
