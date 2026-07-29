<?php

// Quick verification script - save as verify_users.php in project root

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

echo "\n=== USER VERIFICATION ===\n\n";

$users = User::all();

if ($users->count() > 0) {
    echo '✓ Found '.$users->count()." user(s) in database:\n\n";

    foreach ($users as $user) {
        echo "User ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Created: {$user->created_at}\n";
        echo "---\n";
    }
} else {
    echo "✗ No users found in database\n";
}

echo "\n=== LOGIN CREDENTIALS ===\n\n";
echo "Email: admin@centralbazar.com\n";
echo "Password: Admin@12345\n\n";
echo "Email: john.csa@centralbazar.com\n";
echo "Password: Staff@12345\n\n";

echo "Login at: http://localhost:8000/login\n\n";
