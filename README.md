# Central Bazaar Incentive System - Admin Website

## Overview

The Central Bazaar Incentive System is a specialized platform designed to manage and calculate sales incentives for employees across various stores. This repository contains the **Admin Website**, which serves as the central hub for administrative tasks, bulk data management, and incentive configuration.

### Dual-Website Architecture

The overall system is divided into two distinct components:
1.  **Admin Website (This Repo)**:
    *   Used by Central Office Admins.
    *   Handles Store, Designation, Employee, and Product management.
    *   Supports **Bulk Sales Uploads** (Admin Confirmed Sales) from store registers.
    *   Configures incentive percentages and calculates final payouts.
2.  **User Website (External)**:
    *   Used by Store & Operations Employees and Sales Managers (SM).
    *   Employees log in to enter their **Sales Claims**.
    *   Sales Managers verify employee claims by comparing them with the **Admin Confirmed Sales** uploaded here.

## Technical Stack

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Database**: MySQL
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Authentication**: Laravel Breeze
-   **Excel Handling**: Maatwebsite Excel

## Core Features

-   **Bulk Sales Upload**: Upload daily sales data via Excel/CSV. Includes a draft/preview system for validation before finalization.
-   **Incentive Management**: Define incentive splits across roles (CSA, SM, Cluster Manager, etc.).
-   **Role-Based Distribution**: Automatically allocates a percentage of the sales margin to eligible employees.
-   **Store Clustering**: Support for Cluster Managers overseeing multiple stores.

## Sales & Incentive Lifecycle

1.  **Admin Upload**: Admin confirmed sales data is uploaded to this system (`admin_sale_uploads`).
2.  **Employee Claims**: Employees enter their sales on the User website (`daily_store_sales`).
3.  **SM Verification**: Store Managers verify claims by matching them against the Admin confirmed data.
4.  **Incentive Calculation**: Once verified, incentives are calculated based on the defined margins and split percentages.

## Setup & Installation

1.  **Clone & Install**:
    ```bash
    composer install
    npm install
    ```
2.  **Environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3.  **Database**:
    Update `.env` with your MySQL credentials, then:
    ```bash
    php artisan migrate
    php artisan db:seed
    ```
4.  **Run**:
    ```bash
    npm run dev
    php artisan serve
    ```

## Development Tools

-   **Test Incentive Calculation**: `php artisan incentive:test {store_id?}`
-   **Bulk Upload Processing**: Queue-based background processing for large files.

---
*Proprietary software for Central Bazaar Incentive System.*
