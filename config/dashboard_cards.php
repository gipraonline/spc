<?php

/*
|--------------------------------------------------------------------------
| Dashboard cards that can be shown / hidden per designation
|--------------------------------------------------------------------------
| Edit a designation (Admin > Designations > Edit) to choose which of these
| cards its employees see on the unified dashboard. To add a new card:
|   1. add it here,
|   2. wrap it in @if($show('your_key')) in resources/views/dashboard-unified.blade.php.
| Cards that are missing from a designation's saved settings stay hidden
| until someone ticks them, so new cards never leak to a restricted role.
*/
return [

    'groups' => [
        'sales' => 'Sales cards',
        'hr'    => 'HR cards',
    ],

    'cards' => [
        // ---- Sales -----------------------------------------------------
        'qa_sales_orders'  => ['group' => 'sales', 'label' => 'Quick action: Sales Orders'],
        'qa_customers'     => ['group' => 'sales', 'label' => 'Quick action: Customers'],
        'kpi_customers'    => ['group' => 'sales', 'label' => 'Total Customers'],
        'kpi_todays_sales' => ['group' => 'sales', 'label' => "Today's Sales"],
        'kpi_total_sales'  => ['group' => 'sales', 'label' => 'Total Sales'],
        'sales_graphs'     => ['group' => 'sales', 'label' => 'Sales Graphs (trend + order mix)'],
        'order_lifecycle'  => ['group' => 'sales', 'label' => 'Order Lifecycle'],
        'payment_overview' => ['group' => 'sales', 'label' => 'Payment Overview'],

        // ---- HR --------------------------------------------------------
        'hr_quick_actions'     => ['group' => 'hr', 'label' => 'HR quick actions'],
        'hr_notices'           => ['group' => 'hr', 'label' => 'Notices ticker'],
        'hr_kpis'              => ['group' => 'hr', 'label' => 'HR at a glance (KPIs)'],
        'hr_pending_approvals' => ['group' => 'hr', 'label' => 'Pending Approvals'],
        'hr_distribution'      => ['group' => 'hr', 'label' => 'Employee Distribution'],
        'hr_snapshot'          => ['group' => 'hr', 'label' => 'My HR Snapshot'],
        'hr_next_holiday'      => ['group' => 'hr', 'label' => 'Next Holiday'],
    ],
];
