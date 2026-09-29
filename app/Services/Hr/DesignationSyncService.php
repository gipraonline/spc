<?php

namespace App\Services\Hr;

use App\Models\DesignationMaster;
use App\Models\EmployeeMaster;
use App\Models\Hr\Designation;
use App\Models\Hr\JobRequisition;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Keeps the two designation lists in step.
 *
 *  - spc.designation_masters  -> master list (roles, employees, org chart)
 *  - spc_hr.designations      -> HR list (title + department)
 *
 * A master designation can have several HR rows (one per department, e.g.
 * "Farm Care Officer" under 3 departments), so HR rows are matched on
 * title + department, while the master is matched on title alone.
 * Everything is find-or-create, so adding from either module is safe to
 * repeat and never produces duplicates.
 */
class DesignationSyncService
{
    /** Validation rule shared by both "add designation" forms. */
    public const TITLE_RULES = ['required', 'string', 'max:30', 'regex:/^[A-Za-z &\/]+$/'];

    /**
     * Add from the SPC Designations module (master row already created):
     * make sure HR has the matching title, under the chosen department.
     */
    public static function pushMasterToHr(DesignationMaster $master, ?int $departmentId = null): Designation
    {
        return self::findOrCreateHr($master->c_designation, $departmentId);
    }

    /**
     * Add from the HR Organization module: make sure the master list has the
     * title (creating it with an auto identifier / level if new), then create
     * the HR row. Both writes commit together or not at all.
     */
    public static function createFromHr(string $title, ?int $departmentId, ?int $parentId = null): Designation
    {
        $master = DB::connection(config('database.default'));
        $hr = DB::connection('spc_hr');

        $master->beginTransaction();
        $hr->beginTransaction();

        try {
            if (! DesignationMaster::where('c_designation', $title)->exists()) {
                $parent = $parentId ? DesignationMaster::find($parentId) : null;

                DesignationMaster::create([
                    'c_designation' => $title,
                    'identifier' => self::uniqueIdentifier($title),
                    'parent_designation_id' => $parent?->n_designation_id,
                    'hierarchy_level' => $parent ? $parent->hierarchy_level + 1 : 1,
                    'c_status' => 'Y',
                ]);
            }

            $designation = self::findOrCreateHr($title, $departmentId);

            $hr->commit();
            $master->commit();

            return $designation;
        } catch (\Throwable $e) {
            $hr->rollBack();
            $master->rollBack();

            throw $e;
        }
    }

    /** Rename on one side -> rename the counterpart(s) on the other. */
    public static function renameEverywhere(string $oldTitle, string $newTitle): void
    {
        if (strcasecmp($oldTitle, $newTitle) === 0) {
            return;
        }

        DesignationMaster::where('c_designation', $oldTitle)->update(['c_designation' => $newTitle]);
        Designation::where('title', $oldTitle)->update(['title' => $newTitle]);
    }

    /**
     * Delete from the SPC Designations module: removes the master row and
     * every HR row with the same title. Throws a RuntimeException (with a
     * user-facing message) if anything still depends on it.
     */
    public static function deleteFromMaster(DesignationMaster $master): void
    {
        self::assertMasterFree($master);

        $hrRows = Designation::where('title', $master->c_designation)->get();

        foreach ($hrRows as $row) {
            self::assertHrFree($row);
        }

        self::inBothDatabases(function () use ($master, $hrRows) {
            Designation::whereIn('id', $hrRows->pluck('id'))->delete();
            $master->delete();
        });
    }

    /**
     * Delete from the HR Organization module: removes that HR row, and the
     * master row too once no other HR row uses the title and nothing in SPC
     * (employees, roles, reporting lines) still depends on it.
     */
    public static function deleteFromHr(Designation $designation): bool
    {
        // An identical twin (same title + same department) is just a duplicate:
        // move its employees / requisitions onto the twin instead of blocking.
        $twin = self::twinOf($designation);

        if ($twin) {
            DB::connection('spc_hr')->transaction(function () use ($designation, $twin) {
                self::repoint($designation, $twin);
                $designation->delete();
            });

            return true; // merged into the twin
        }

        self::assertHrFree($designation);

        $title = $designation->title;

        self::inBothDatabases(function () use ($designation, $title) {
            $designation->delete();

            $stillInHr = Designation::where('title', $title)->exists();
            $master = DesignationMaster::where('c_designation', $title)->first();

            if (! $stillInHr && $master) {
                self::assertMasterFree($master);
                $master->delete();
            }
        });

        return false;
    }

    /**
     * Merge every group of identical HR designations (same title + department)
     * into the oldest row of the group. Returns [group count, rows removed].
     */
    public static function mergeDuplicates(bool $dryRun = false, ?callable $report = null): array
    {
        $groups = Designation::orderBy('id')->get()
            ->groupBy(fn ($d) => mb_strtolower(trim($d->title)).'|'.($d->department_id ?? 'null'))
            ->filter(fn ($g) => $g->count() > 1);

        $removed = 0;

        foreach ($groups as $rows) {
            $keeper = $rows->first();

            foreach ($rows->slice(1) as $dup) {
                if ($report) {
                    $report($keeper, $dup);
                }

                if (! $dryRun) {
                    DB::connection('spc_hr')->transaction(function () use ($dup, $keeper) {
                        self::repoint($dup, $keeper);
                        $dup->delete();
                    });
                }

                $removed++;
            }
        }

        return [$groups->count(), $removed];
    }

    protected static function twinOf(Designation $designation): ?Designation
    {
        return Designation::where('id', '!=', $designation->id)
            ->where('title', $designation->title)
            ->when(
                $designation->department_id,
                fn ($q) => $q->where('department_id', $designation->department_id),
                fn ($q) => $q->whereNull('department_id'),
            )
            ->orderBy('id')
            ->first();
    }

    /** Move everything that points at $from over to $to. */
    protected static function repoint(Designation $from, Designation $to): void
    {
        $hr = DB::connection('spc_hr');

        $hr->table('employees')->where('designation_id', $from->id)->update(['designation_id' => $to->id]);
        $hr->table('job_requisitions')->where('designation_id', $from->id)->update(['designation_id' => $to->id]);
    }

    protected static function assertMasterFree(DesignationMaster $master): void
    {
        $name = $master->c_designation;

        if (EmployeeMaster::where('n_designation_id', $master->n_designation_id)->exists()) {
            $n = EmployeeMaster::where('n_designation_id', $master->n_designation_id)->count();

            throw new \RuntimeException("\"{$name}\" is assigned to {$n} employee".($n > 1 ? 's' : '').' in SPC. Reassign them to another designation first.');
        }

        if ($master->children()->exists()) {
            throw new \RuntimeException("\"{$name}\" has designations reporting to it. Move them first.");
        }

        if ($master->identifier && Role::where('identifier', $master->identifier)->exists()) {
            throw new \RuntimeException("\"{$name}\" is linked to a role and can't be deleted.");
        }
    }

    protected static function assertHrFree(Designation $designation): void
    {
        $employees = $designation->employees()->count();
        $requisitions = JobRequisition::where('designation_id', $designation->id)->count();

        if ($employees || $requisitions) {
            $dept = $designation->department?->name ?? 'no department';
            $used = collect([
                $employees ? $employees.' HR employee'.($employees > 1 ? 's' : '') : null,
                $requisitions ? $requisitions.' job requisition'.($requisitions > 1 ? 's' : '') : null,
            ])->filter()->implode(' and ');

            throw new \RuntimeException(
                "\"{$designation->title}\" ({$dept}) is used by {$used}. Reassign them to another designation first."
            );
        }
    }

    protected static function inBothDatabases(\Closure $work): void
    {
        $master = DB::connection(config('database.default'));
        $hr = DB::connection('spc_hr');

        $master->beginTransaction();
        $hr->beginTransaction();

        try {
            $work();
            $hr->commit();
            $master->commit();
        } catch (\Throwable $e) {
            $hr->rollBack();
            $master->rollBack();

            throw $e;
        }
    }

    protected static function findOrCreateHr(string $title, ?int $departmentId): Designation
    {
        return Designation::firstOrCreate([
            'title' => $title,
            'department_id' => $departmentId,
        ]);
    }

    protected static function uniqueIdentifier(string $title): string
    {
        $base = Str::upper(Str::slug($title, '_')) ?: 'DESIG';
        $base = Str::limit($base, 44, '');
        $identifier = $base;
        $i = 2;

        while (DesignationMaster::where('identifier', $identifier)->exists()) {
            $identifier = $base.'_'.$i++;
        }

        return $identifier;
    }
}
