<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\AuditLog;
use App\Models\Hr\Employee;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SystemController extends Controller
{
    /** Areas shown as filter chips: module key => label. */
    private const AREAS = [
        'users' => 'Users & access',
        'payroll' => 'Payroll',
        'settings' => 'Settings',
    ];

    private const ROLE_LABELS = [
        'employee' => 'Employee',
        'manager' => 'Reporting Manager',
        'hr_admin' => 'HR Admin',
        'super_admin' => 'Super Admin',
    ];

    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('system');

        $area = $request->query('area');
        $query = AuditLog::with('user')->orderByDesc('id');

        if (isset(self::AREAS[$area])) {
            $query->where('module', $area);
        } elseif ($area === 'other') {
            $query->whereNotIn('module', array_keys(self::AREAS));
        } else {
            $area = null;
        }

        $auditLog = $query->paginate(20, ['*'], 'auditPage')->withQueryString();
        $auditLog->getCollection()->transform(function ($log) {
            $log->summary = $this->describe($log);

            return $log;
        });

        $perModule = AuditLog::selectRaw('module, COUNT(*) as total')->groupBy('module')->pluck('total', 'module');
        $totalAuditEntries = (int) $perModule->sum();
        $areaCounts = ['all' => $totalAuditEntries];
        foreach (array_keys(self::AREAS) as $key) {
            $areaCounts[$key] = (int) ($perModule[$key] ?? 0);
        }
        $areaCounts['other'] = $totalAuditEntries - array_sum(array_intersect_key($areaCounts, self::AREAS));

        return view('hr.modules.system', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'system',
            'auditLog' => $auditLog,
            'totalAuditEntries' => $totalAuditEntries,
            'todayCount' => AuditLog::where('created_at', '>=', now()->startOfDay())->count(),
            'areas' => self::AREAS + ['other' => 'Other'],
            'areaCounts' => $areaCounts,
            'area' => $area,
        ]));
    }

    /* ------------------------------------------------------------------ */
    /*  Plain-language description of an audit entry                      */
    /* ------------------------------------------------------------------ */

    /** @return array{title:string, lines:array<int,string>, tone:string, icon:string} */
    private function describe(AuditLog $log): array
    {
        $old = $this->decode($log->old_value);
        $new = $this->decode($log->new_value);
        $action = strtoupper((string) $log->action);

        $tone = match (true) {
            str_contains($action, 'CREATE'), $action === 'PAYROLL_RUN' => 'add',
            str_contains($action, 'DELETE'), str_contains($action, 'DISCARD') => 'remove',
            default => 'edit',
        };

        $title = null;
        $lines = [];
        $icon = match ($tone) {
            'add' => 'fa-plus',
            'remove' => 'fa-trash-can',
            default => 'fa-pen',
        };

        switch ($log->module) {
            case 'users':
                $name = ($log->record_id ? User::find($log->record_id)?->name : null) ?? ($new['name'] ?? 'a user');
                $icon = 'fa-user-gear';
                if ($action === 'CREATE') {
                    $title = 'Added user '.$name;
                    $lines = $this->diff([], $new);
                } elseif ($action === 'UPDATE') {
                    $title = 'Changed access for '.$name;
                    $lines = $this->diff($old, $new);
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
                    $lines = $action === 'DELETE' ? $this->diff([], $old) : ($action === 'CREATE' ? $this->diff([], $new) : $this->diff($old, $new));
                } elseif ($action === 'UPDATE') {
                    $key = array_key_first($new) ?? array_key_first($old);
                    if ($key !== null) {
                        $title = 'Changed setting: '.$this->label($key);
                        $lines[] = $this->value($key, $old[$key] ?? null).' → '.$this->value($key, $new[$key] ?? null);
                    }
                }
                break;
        }

        if ($title === null) {
            $title = ucfirst(strtolower(str_replace('_', ' ', $action))).' in '.str_replace('_', ' ', (string) $log->module);
            $lines = $this->diff($old, $new);
        }

        return ['title' => $title, 'lines' => $lines, 'tone' => $tone, 'icon' => $icon];
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
    private function diff(array $old, array $new): array
    {
        $skip = ['leave_type', 'name', 'applied_to_existing'];
        $source = $new;
        $lines = [];

        foreach ($source as $key => $_) {
            if (in_array($key, $skip, true)) {
                continue;
            }
            $before = $this->value($key, $old[$key] ?? null);
            $after = $this->value($key, $new[$key] ?? null);
            if ($old && $before === $after) {
                continue;
            }
            $lines[] = $this->label($key).': '.($old ? $before.' → '.$after : $after);
        }

        if (($new['applied_to_existing'] ?? false) === true) {
            $lines[] = 'Applied to every employee\'s current balance';
        }

        return $lines;
    }

    private function label(string $key): string
    {
        $special = [
            'is_active' => 'Status',
            'default_annual_days' => 'Days per year',
            'max_carry_forward' => 'Max days carried forward',
            'carry_forward' => 'Carry forward',
        ];
        if (isset($special[$key])) {
            return $special[$key];
        }

        $words = ['pf' => 'PF', 'esi' => 'ESI', 'pt' => 'Professional tax', 'tds' => 'TDS', 'wfh' => 'WFH', 'edli' => 'EDLI'];
        $parts = array_map(fn ($w) => $words[$w] ?? $w, explode('_', $key));

        return ucfirst(implode(' ', $parts));
    }

    private function value(string $key, $v): string
    {
        if ($key === 'is_active') {
            return $v ? 'Active' : 'Suspended';
        }
        if ($key === 'role') {
            return self::ROLE_LABELS[$v] ?? ($v === null ? 'none' : (string) $v);
        }
        if (is_bool($v) || in_array($key, ['carry_forward', 'applied_to_existing'], true)) {
            return $v ? 'Yes' : 'No';
        }
        if ($v === null || $v === '') {
            return 'empty';
        }
        if (is_numeric($v)) {
            return rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.') ?: '0';
        }

        return (string) $v;
    }
}