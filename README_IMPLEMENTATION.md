# Central Bazaar Incentive Admin System - Overview

## 🎯 Project Overview

A comprehensive Laravel-based application for managing and calculating sales incentives. This repository contains the **Admin Website**, which automates the high-level configuration and ingest of bulk register data.

**Key Mechanism**: The system calculates incentives as **20% of the sales margin**, which is then distributed across designated employee pools.

## 📋 System Requirements

✅ **Margin-Based Incentive Pool**
- Total Sales Margin (Price - Cost) → 20% Pool → Distributed across roles.

✅ **Distribution Mechanism**
- Flexible split percentages defined in `product_incentives`.
- Common distribution: CSA (60%), SM (5%), Cluster (5%), HO (10%), etc.

✅ **Admin Features**
- Bulk Sales Upload (Excel/CSV) with Draft & Validation system.
- Comprehensive Master Data Management (Stores, Employees, Products, Designations).
- Manual Sales Entry for corrections.
- Incentive Calculation & Reporting.

## 🚀 Quick Start

1.  **Dependencies**: `composer install` and `npm install`.
2.  **Environment**: `cp .env.example .env` and `php artisan key:generate`.
3.  **Database**: Update `.env`, then `php artisan migrate` and `php artisan db:seed`.
4.  **Run**: `php artisan serve` and `npm run dev`.

Access at: **http://localhost:8000/login**

## 📊 Logic & Workflow

1.  **Admin Uploads**: Daily register data is uploaded to `admin_sale_uploads`.
2.  **Employees Claim**: Sales are entered by employees on the **User Website** (`daily_store_sales`).
3.  **SM Verifies**: Store Managers verify claims by matching them against Admin uploads.
4.  **Incentive Calculation**: The Admin Website calculates payouts based on verified claims and defined split percentages.

## 📁 Key Files

- **Incentive Service**: `app/Services/IncentiveCalculationService.php`
- **Sales Controller**: `app/Http/Controllers/Admin/SalesController.php` (Handling bulk uploads)
- **Models**: `AdminSaleUpload`, `AdminSaleDraft`, `DailyStoreSale`.

---
*Note: This repository only contains the Admin Website. The Staff/User portal is a separate repository.*
**http://localhost:8000**

## 💡 Usage

### For Administrators

1. **Setup System**
   - Create designations (CSA, SM, C&A, ASM, CASHIER, etc.)
   - Register stores
   - Add employees to stores and designations
   - Configure products

2. **Record Sales**
   - Enter daily sales transactions
   - Or bulk upload via Excel/CSV

3. **Calculate Incentives**
   - Select store and date range
   - System automatically calculates distribution
   - View detailed breakdown by employee

### For Staff

1. **Personal Dashboard**
   - View sales summary
   - Check incentive calculations
   - Track monthly performance

2. **Sales Entry**
   - Record personal sales
   - Track incentive earned

## 📊 Example Calculation

**Input:**
- Store: Main Store
- Total Sales: ₹10,000
- Period: March 2026

**Processing:**
```
Incentive Pool (20%): ₹2,000

Distribution:
├── CSA (60%): ₹1,200
│   ├── Employee 1: ₹600
│   └── Employee 2: ₹600
├── SM (5%): ₹100
├── Support (5%): ₹100
│   ├── C&A Staff: ₹50
│   └── CASHIER: ₹50
└── Other Pools (5% each): ₹100 each
    ├── CLUSTER: ₹100
    ├── OPERATIONS: ₹100
    ├── B&M: ₹100
    ├── DC: ₹100
    └── HO: ₹100
```

## 🗄️ Database Schema

### Core Tables
- `admin_roles` - Role definitions
- `pool_masters` - Incentive pools
- `operations` - Business units
- `store_masters` - Store locations
- `designation_masters` - Employee designations
- `employee_masters` - Employee records
- `employee_types` - Employee pool assignments
- `item_masters` - Product catalog
- `incentive_masters` - Incentive rules
- `employee_incentives` - Employee-specific incentives
- `sales_masters` - Sales transactions
- `users` - Authentication

## 🛣️ API Routes

### Admin Routes (Protected)
```
GET    /admin/designations           List all designations
POST   /admin/designations           Create new
GET    /admin/designations/create    Create form
PUT    /admin/designations/{id}      Update
DELETE /admin/designations/{id}      Delete

GET    /admin/stores                 List all stores
POST   /admin/stores                 Create new
...similar pattern for all resources

GET    /admin/incentives             Calculator page
POST   /admin/incentives/calculate   Calculate incentives
GET    /admin/incentives/summary     Summary page
```

### Staff Routes (Protected)
```
GET    /staff/dashboard              Personal dashboard
GET    /staff/sales                  My sales
POST   /staff/sales                  Create sales entry
PUT    /staff/sales/{id}             Update sales
DELETE /staff/sales/{id}             Delete sales
```

## 📁 Project Structure

```
app/
├── Console/Commands/
│   └── TestIncentiveCalculation.php
├── Http/Controllers/
│   ├── Admin/
│   │   ├── DesignationController.php
│   │   ├── StoreController.php
│   │   ├── EmployeeController.php
│   │   ├── ItemController.php
│   │   ├── SalesController.php
│   │   └── IncentiveController.php
│   └── Staff/
│       ├── DashboardController.php
│       └── SalesEntryController.php
├── Models/
│   ├── AdminRole.php
│   ├── PoolMaster.php
│   ├── StoreMaster.php
│   ├── EmployeeMaster.php
│   ├── ItemMaster.php
│   ├── SalesMaster.php
│   └── ... (11 total)
├── Services/
│   └── IncentiveCalculationService.php
└── Middleware/
    └── AdminMiddleware.php

database/
├── migrations/
│   └── [12 migration files]
└── seeders/
    └── IncentiveSystemSeeder.php

resources/views/
├── admin/
│   ├── designations/
│   ├── stores/
│   ├── employees/
│   ├── items/
│   ├── sales/
│   └── incentives/
└── staff/
    └── sales/
```

## 🧪 Testing

### Test Calculation
```bash
php artisan incentive:test           # Test first store
php artisan incentive:test 1         # Test specific store
```

### Expected Output
```
Total Sales: ₹8,750.00
Incentive Pool (20%): ₹1,750.00

Distribution:
- CSA: ₹1,050.00 (60%)
- C&A/ASM/CASHIER: ₹87.50 (5%)
- SM: ₹87.50 (5%)
- CLUSTER: ₹87.50 (5%)
- OPERATIONS: ₹87.50 (5%)
- B&M: ₹87.50 (5%)
- DC: ₹87.50 (5%)
- HO: ₹87.50 (5%)
```

## 📚 Documentation

- **QUICKSTART.md** - Quick reference guide
- **IMPLEMENTATION.md** - Detailed implementation guide (9,200+ words)
- **IMPLEMENTATION_SUMMARY.md** - Feature summary and checklist
- **INCENTIVE_CALCULATION_DETAILS.md** - Technical deep-dive (11,200+ words)
- **VERIFICATION_REPORT.md** - Verification and testing report

## 🔧 Configuration

### Environment Variables (.env)
```
APP_NAME="Central Bazaar Admin"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=centreal_db
DB_USERNAME=centreal_usr
DB_PASSWORD=fh9poBEbX[l/DJ0j
```

## 🚨 Important Notes

1. **Admin Access**: Currently allows all authenticated users. Implement role-based access in production.

2. **Bulk Upload**: Requires external package (Laravel Excel). Stub provided.

3. **Database**: Uses custom primary key names (e.g., `n_pool_id` instead of `id`).

4. **Authentication**: Built with Laravel Breeze. Customize for your needs.

## 🎨 Features Implemented

### ✅ Complete
- Full CRUD for designations, stores, employees, items
- Sales transaction management
- Incentive calculation with verification
- Database seeding with sample data
- Artisan test command
- Multiple views and controllers

### ⏳ Future Enhancements
- Excel bulk upload
- KYC approval workflow
- Incentive withdrawal
- Advanced reporting
- PDF export
- Email notifications
- Real-time dashboards

## 🔐 Security

- ✅ CSRF protection
- ✅ Middleware authentication
- ✅ Mass assignment protection
- ✅ SQL injection prevention (Eloquent)
- ✅ Environment variables for sensitive data

**To Enable in Production:**
- Implement role-based access control
- Add 2FA authentication
- Enable HTTPS
- Setup logging and monitoring
- Regular security audits

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Verify .env settings and MySQL is running
mysql -u centreal_usr -p -h 127.0.0.1 centreal_db
```

### Migration Issues
```bash
php artisan config:clear
php artisan migrate:reset
php artisan migrate
```

### Routes Not Found
```bash
php artisan route:clear
php artisan optimize
```

## 📞 Support

For issues or questions:
1. Check documentation files
2. Review test command output
3. Verify database tables
4. Check error logs in `storage/logs/`

## 📄 License

This project is proprietary software for Central Bazaar.

## 🎯 Next Steps

1. **Deploy to Staging**
   - Setup staging environment
   - Run comprehensive testing
   - Verify calculations with real data

2. **User Training**
   - Train admin staff on system
   - Create user documentation
   - Setup support process

3. **Go Live**
   - Backup production database
   - Deploy to production
   - Monitor system performance

## 📊 System Statistics

- **Models**: 11 Eloquent models
- **Controllers**: 8 (6 Admin + 2 Staff)
- **Routes**: 44 endpoints
- **Views**: 10+ templates
- **Migrations**: 12 table definitions
- **Database Tables**: 12 tables
- **Lines of Code**: 2,000+

## ✨ Key Innovation

The system's innovation lies in its sophisticated multi-pool incentive distribution that can be easily customized. By separating designation pools and allowing flexible percentages, it provides:

- **Fairness**: Equal treatment within pools
- **Transparency**: Clear calculation methodology
- **Scalability**: Easy to add new pools or designations
- **Flexibility**: Adjustable percentages per store or period

---

**Version**: 1.0  
**Last Updated**: 2026-03-20  
**Status**: ✅ Production Ready (with caveats noted above)
