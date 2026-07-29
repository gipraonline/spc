# Quick Start Guide - Central Bazaar Incentive System

## Installation

```bash
# 1. Clone and install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
# DB_DATABASE=centreal_db
# DB_USERNAME=centreal_usr
# DB_PASSWORD=your_password

# 4. Create tables and seed data
php artisan migrate
php artisan db:seed

# 5. Build frontend
npm run build

# 6. Start server
php artisan serve
```

## Access URLs

- **Application**: http://localhost:8000
- **Admin Login**: http://localhost:8000/login
- **Admin Dashboard**: http://localhost:8000/admin/designations

## Admin Workflows

### 1. Bulk Sales Upload
```
Admin → Sales → Bulk Upload
- Upload Excel/CSV from store registers.
- Review drafts and validation errors.
- Confirm valid sales to move them to confirmed storage.
```

### 2. Manual Configuration
- **Designations**: Create roles like CSA, SM, Cluster Manager.
- **Stores**: Define store locations and codes.
- **Employees**: Register employees and link them to stores/designations.
- **Products**: Add items with purchase and selling prices.

### 3. Incentive Calculation (Overview)
- **Pool**: 20% of the sales margin.
- **Verification**: SM verifies employee claims (on the User Website) against the uploaded sales.
- **Calculation**: Triggered via Admin → Incentives → Calculate.

## Key Calculations

**Margin** = (Selling Price - Purchase Price) × Quantity
**Incentive Pool** = 20% of Margin

**Distribution Example**:
- Store Margin: ₹10,000
- Incentive Pool (20%): ₹2,000
- **CSA (60% of Pool)**: ₹1,200
- **SM (5% of Pool)**: ₹100
- **Cluster (5% of Pool)**: ₹100
- ...and other pools as configured.

## Testing & Verification

### Test Command
```bash
php artisan incentive:test
# or for specific store
php artisan incentive:test {store_id}
```

## Core Database Tables

| Table | Purpose |
|-------|---------|
| admin_sale_uploads | Confirmed sales for verification |
| admin_sale_drafts | Temporary bulk upload storage |
| daily_store_sales | Employee sales claims |
| employee_incentives | Persisted incentive results |
| store_masters | Store locations |
| product_masters | Product catalog |
| employee_masters | Employee records |

---
*Note: The Employee/Staff portal is managed in a separate repository.*
