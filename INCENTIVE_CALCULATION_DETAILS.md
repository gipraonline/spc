# Incentive Calculation System - Technical Documentation

## Overview

The Central Bazaar Incentive Calculation System implements a distribution mechanism where a percentage of the total sales margin is allocated across multiple designation-based pools.

## Core Distribution Formula

The incentive pool is derived from the sales margin:

```
Margin = (Selling Price - Purchase Price) × Quantity
Incentive Pool = Margin × 20%
```

This 20% pool is then further distributed according to the designation-specific percentages defined in the `product_incentives` table.

## Sales & Incentive Lifecycle

The system follows a multi-stage lifecycle across the Admin and User websites:

1.  **Admin Confirmed Sales**: Daily sales data from store registers is uploaded to the Admin Website in bulk (`admin_sale_uploads`).
2.  **Employee Sales Claims**: Employees log in to the User Website and enter their individual sales claims (`daily_store_sales`).
3.  **SM Verification**: The Store Manager (SM) verifies the employee claims by comparing them with the Admin confirmed sales data.
4.  **End-of-Day (EOD) Calculation (Planned Outline)**:
    *   Once claims are verified (marked as approved), the system triggers the incentive calculation.
    *   The `IncentiveCalculationService` processes each approved sale to distribute the 20% margin pool among eligible employees and designation pools.
    *   Results are persisted in the `employee_incentives` table for reporting and payout.

## Implementation Details

### Core Model: DailyStoreSale

The `DailyStoreSale` model represents the verified sales data used for incentive calculations. Key attributes include:
*   `n_sold_price`: The price at which the item was sold.
*   `n_quantity`: The number of items sold.
*   `total_margin_amount`: A calculated attribute returning 20% of the margin (`0.20 * (selling_price - purchase_price) * quantity`).

### Calculation Service: IncentiveCalculationService

The `IncentiveCalculationService` contains the core logic for distributing the incentive pool.

#### Single Sale Calculation
Calculates and persists incentives for a specific sale record:
```php
public function calculateSaleIncentives($saleId) {
    $sale = DailyStoreSale::find($saleId);
    $baseAmount = $sale->total_margin_amount; // This is the 20% pool
    
    // 1. Direct Salesperson Incentive
    // 2. Pool Incentives (SM, Cluster, Operations, etc.)
}
```

#### Store-wide/Batch Calculation
Aggregates incentives for a store over a date range:
```php
public function calculateStoreIncentives($storeId, $dateRange = null) {
    $sales = DailyStoreSale::where('n_store_id', $storeId)->get();
    // Aggregates total sales and distribution breakdown
}
```

## Calculation Example

**Scenario:**
- Product: Coffee (Purchase: ₹150, Selling: ₹250)
- Quantity: 10
- Total Margin: (250 - 150) * 10 = ₹1,000
- **Incentive Pool (20%)**: ₹200

**Distribution of the ₹200 Pool:**
- **CSA (60%)**: ₹120 (Split among CSAs)
- **SM (5%)**: ₹10
- **Cluster (5%)**: ₹10
- **HO (10%)**: ₹20
- *...and other pools as defined.*

## Verification & Testing

- **Artisan Command**: Use `php artisan incentive:test {store_id}` to dry-run calculations for a store and see the distribution breakdown.
- **Manual Calculation**: Can be verified by checking the `total_margin_amount` attribute on the `DailyStoreSale` model and applying the designation percentages.

---
*Note: The automatic End-of-Day (EOD) processing and synchronization between User and Admin websites are currently under development.*
