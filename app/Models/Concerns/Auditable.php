<?php

namespace App\Models\Concerns;

use App\Services\AuditTrail;
use Illuminate\Support\Str;

/**
 * Add `use Auditable;` to a model and every create / edit / delete of that
 * model is written to the audit log (shown under System & Access).
 *
 * Optional properties on the model:
 *   protected string $auditModule  = 'customers';        // filter key
 *   protected string $auditEntity  = 'Customer';         // "Added Customer …"
 *   protected array  $auditSubjectColumns = ['c_customer_name'];
 *   protected array  $auditIgnore  = ['some_noisy_column'];
 *
 * Override auditSubject() when the readable name lives on a related record.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn ($m) => AuditTrail::fromModel($m, 'created'));
        static::updated(fn ($m) => AuditTrail::fromModel($m, 'updated'));
        static::deleted(fn ($m) => AuditTrail::fromModel($m, 'deleted'));

        if (method_exists(static::class, 'restored')) {
            static::restored(fn ($m) => AuditTrail::fromModel($m, 'restored'));
        }
    }

    public function auditModule(): string
    {
        return $this->auditModule ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    public function auditEntity(): string
    {
        return $this->auditEntity ?? Str::headline(class_basename($this));
    }

    public function auditIgnored(): array
    {
        return $this->auditIgnore ?? [];
    }

    /** Human readable name of this record (order no., customer name, …). */
    public function auditSubject(): ?string
    {
        foreach ($this->auditSubjectColumns ?? ['name', 'title'] as $column) {
            $value = $this->getAttribute($column);
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return null;
    }
}
