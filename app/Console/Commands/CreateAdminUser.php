<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {--email=admin@centralbazar.com} {--password=Admin@12345} {--name="Admin User"}';

    protected $description = 'Create an admin user account';

    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Check if user already exists
        if (Admin::where('c_username', $email)->exists()) {
            $this->error("User with email {$email} already exists!");

            return 1;
        }

        // Create user
        $user = Admin::create([
            'c_role' => 'Super Admin',
            'c_username' => $email,
            'c_password' => bcrypt($password),
            'c_status' => 'Active',
        ]);

        $this->info('✓ Admin user created successfully!');
        $this->line('');
        $this->table(
            ['Property', 'Value'],
            [
                ['User ID', $user->n_role_id],
                ['Name', $name],
                ['Username', $user->c_username],
                ['Password', $password],
                ['Status', $user->c_status],
                ['Role', $user->c_role],
            ]
        );
        $this->line('');
        $this->info('Login at: http://localhost:8000/login');

        return 0;
    }
}
