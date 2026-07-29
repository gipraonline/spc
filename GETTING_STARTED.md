# Getting Started - Central Bazaar Incentive System

## ✅ System Status

The Central Bazaar Incentive Admin Website is ready for configuration and bulk data management.

## 🚀 Quick Start (5 minutes)

### 1. Start the Server
```bash
php artisan serve
```
The application will start at: **http://localhost:8000**

### 2. Login with Admin Credentials
```
URL:      http://localhost:8000/login
Email:    admin@centralbazar.com
Password: Admin@12345
```

### 3. Access Admin Dashboard
Navigate to:
- **http://localhost:8000/admin/designations** - Start configuring roles.

## 📊 Sample Data

The system includes test data to demonstrate the incentive logic:
- **Stores**: 2 stores (Main Store, Branch 2)
- **Employees**: 5 employees across different designations.
- **Products**: Sample catalog with purchase/selling prices.
- **Sales**: Manual and bulk upload examples.

## 🎯 Key Admin Workflows

### 1. Bulk Sales Upload
1. Go to **Admin → Sales → Bulk Upload**.
2. Upload a formatted Excel file (see `admin_upload_format.csv` for example).
3. Process the file and review the **Drafts** for validation errors.
4. Confirm the valid entries to finalize the upload.

### 2. Calculate Incentives
1. Go to **Admin → Incentives**.
2. Select a store and date range.
3. Click "Calculate" to see the distribution based on the 20% margin pool.

## 🧪 Testing via Command Line
```bash
php artisan incentive:test
```
This command runs a dry-run calculation for the first store and displays the incentive distribution across all eligible employees.

## 🛣️ Important URLs

| Page | URL | Description |
|------|-----|-------------|
| Login | http://localhost:8000/login | Admin authentication |
| Designations | http://localhost:8000/admin/designations | Role configuration |
| Stores | http://localhost:8000/admin/stores | Store management |
| Employees | http://localhost:8000/admin/employees | Employee registration |
| Sales | http://localhost:8000/admin/sales | Upload & manual entries |
| Incentives | http://localhost:8000/admin/incentives | Pool calculation |

## 💡 Troubleshooting

- **Login Issues**: Ensure the seeder has been run to create the default admin account.
- **Calculation Mismatch**: Verify the purchase/selling prices of products and the split percentages in `product_incentives`.
- **Upload Failures**: Check that store and product codes in your Excel match the ones in the system.

---
*Note: This repository only contains the Admin Website. Employee sales entry and SM verification occur on the separate User Website.*
