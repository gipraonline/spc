# Central Bazaar Incentive Calculation System - Implementation Documentation

## Overview

The Central Bazaar Incentive Calculation System is a Laravel-based application designed to automate the distribution of sales incentives. This repository contains the **Admin Website**, which handles the high-level configuration, bulk data ingestion, and final incentive distribution.

## Core Features

### Sales Management
1.  **Bulk Sales Upload**: Admins can upload daily sales data via Excel/CSV.
    *   **Draft System**: Uploaded data is first stored in a draft table for validation.
    *   **Validation**: Checks for valid store codes, product codes, and data formats.
    *   **Confirmation**: Validated drafts are moved to the confirmed sales table.
2.  **Manual Sales Entry**: Ability to manually record sales for testing or correction.

### Administration
1.  **Store Management**: Manage store details and contact information.
2.  **Product Management**: Maintain the product catalog with purchase and selling prices.
3.  **Employee Management**: Register employees with store and designation assignments.
4.  **Incentive Configuration**: Define split percentages for various roles (SM, CSA, Cluster, etc.).

### Incentive Calculation
1.  **Margin-Based Pool**: Incentives are calculated as 20% of the sales margin.
2.  **Automated Distribution**: The pool is split among eligible employees based on their designation and store/pool association.
3.  **Reporting**: View summaries and detailed breakdowns of earned incentives.

## Technical Stack

-   **Framework**: Laravel 12
-   **PHP**: 8.2+
-   **Database**: MySQL
-   **Frontend**: Tailwind CSS, Blade Templates
-   **Excel Processing**: Maatwebsite Excel

## Database Schema

### Key Tables

-   **admin_sale_uploads**: Confirmed sales data uploaded by the admin.
-   **admin_sale_drafts**: Temporary storage for bulk uploads undergoing validation.
-   **daily_store_sales**: Employee sales claims (verified by SM before calculation).
-   **employee_incentives**: Persisted results of incentive calculations.
-   **store_masters**: Store details.
-   **product_masters**: Product catalog.
-   **employee_masters**: Employee records with store and designation links.
-   **designation_masters**: List of roles (CSA, SM, etc.).
-   **product_incentives**: Configuration for incentive splits per product.

## Project Structure

```
app/
├── Console/Commands/
│   └── TestIncentiveCalculation.php
├── Http/Controllers/Admin/
│   ├── SalesController.php        # Bulk upload & draft management
│   ├── IncentiveController.php    # Calculation & reporting
│   ├── EmployeeController.php     # Employee management
│   └── ...
├── Models/
│   ├── AdminSaleUpload.php
│   ├── AdminSaleDraft.php
│   ├── DailyStoreSale.php
│   ├── EmployeeIncentive.php
│   └── ...
├── Services/
│   └── IncentiveCalculationService.php # Core logic
├── Jobs/
│   └── ProcessSalesUpload.php      # Async bulk processing
└── Imports/
    └── SalesImport.php            # Excel parsing logic
```

## Setup Instructions

1.  **Install PHP & JS dependencies**: `composer install` and `npm install`.
2.  **Configuration**: Copy `.env.example` to `.env` and set up database credentials.
3.  **Migrations**: Run `php artisan migrate`.
4.  **Assets**: Run `npm run dev` or `npm run build`.

## Verification Tools

-   **Test Command**: `php artisan incentive:test` to verify calculations for a store.
-   **Draft Preview**: Use the Admin UI to review and fix upload errors before final confirmation.

---
*Note: The Staff/Employee portal is a separate repository and is not included in this project.*
