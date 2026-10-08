<?php

/*
|--------------------------------------------------------------------------
| Audit log areas (System & Access page)
|--------------------------------------------------------------------------
| Every audited model / action has a "module" key (see $auditModule on the
| model, or AuditTrail::record()). The filter chips on the System page group
| those modules into areas. To audit a new model: add
|     use \App\Models\Concerns\Auditable;
| to it, set $auditModule / $auditEntity, and list the module below.
*/

return [

    'areas' => [
        'sales' => ['label' => 'Sales', 'modules' => ['sales_orders', 'customers', 'products', 'franchises', 'leads']],
        'tasks' => ['label' => 'Tasks', 'modules' => ['tasks']],
        'people' => ['label' => 'Employees', 'modules' => ['employees', 'designations', 'exits', 'documents', 'organization']],
        'users' => ['label' => 'Users & access', 'modules' => ['users', 'roles']],
        'leave' => ['label' => 'Leave & attendance', 'modules' => ['leave', 'wfh', 'attendance']],
        'hr' => ['label' => 'HR activities', 'modules' => ['appraisal', 'recruitment', 'incentives', 'pf', 'announcements', 'support']],
        'payroll' => ['label' => 'Payroll', 'modules' => ['payroll']],
        'settings' => ['label' => 'Settings', 'modules' => ['settings']],
    ],

    /** Older rows written before modules were standardised. */
    'legacy_modules' => [
        'SalesOrdercreate' => 'sales_orders',
        'SalesOrderUpdate' => 'sales_orders',
    ],

    /** Used in the fallback title when an entry carries no entity name. */
    'module_labels' => [
        'sales_orders' => 'Sales order', 'customers' => 'Customer', 'products' => 'Product',
        'franchises' => 'Franchise', 'leads' => 'Lead', 'tasks' => 'Task', 'employees' => 'Employee',
        'designations' => 'Designation', 'exits' => 'Exit record', 'documents' => 'Document',
        'organization' => 'Organization', 'users' => 'User', 'roles' => 'Role / permission',
        'leave' => 'Leave', 'wfh' => 'Work from home', 'attendance' => 'Attendance',
        'appraisal' => 'Appraisal', 'recruitment' => 'Recruitment', 'incentives' => 'Incentive',
        'pf' => 'PF', 'announcements' => 'Announcement', 'support' => 'Support ticket',
        'payroll' => 'Payroll', 'settings' => 'Settings',
    ],
];
