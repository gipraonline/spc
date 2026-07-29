# Login Credentials

## Admin User
```
Email:    admin@centralbazar.com
Password: Admin@12345
Role:     System Administrator
```

## Admin Features Available

Once logged in as admin, you can:
- Manage Designations
- Manage Stores
- Manage Employees
- Manage Items (Products)
- Record Sales Transactions
- Calculate Incentives
- View Reports

## Staff Features Available

Once logged in as staff, you can:
- View Personal Dashboard
- View Personal Sales History
- Record New Sales
- View Incentive Tracking

## Create Additional Users

To create more users with custom credentials:

```bash
php artisan admin:create --name="Username" --email="user@example.com" --password="Password123"
```

Example:
```bash
php artisan admin:create --name="Manager Smith" --email="manager@centralbazar.com" --password="Manager@123"
```

## Reset Password

If you forget the password, you can create a new user or use:

```bash
php artisan tinker
# Then in tinker:
$user = User::where('email', 'admin@centralbazar.com')->first();
$user->password = bcrypt('NewPassword@123');
$user->save();
exit();
```

## Security Notes

- Change the default admin password after first login
- Use strong, unique passwords
- Do not share credentials
- Use HTTPS in production
- Enable 2FA when available

---
