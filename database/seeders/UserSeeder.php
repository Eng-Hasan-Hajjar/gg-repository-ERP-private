<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'eng.hasan.hajjar@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('123456789'),
            ]
        );



          // 🔐 Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'eng.hasan.hajjar2@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123456789'), // غيّرها لاحقًا
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::firstOrCreate(
                ['name' => 'super_admin'],
                ['label' => 'سوبر أدمين']
            );

            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);


        // (اختياري) Admin عادي
        $admin = User::firstOrCreate(
            ['email' => 'eng.hasan.hajjar3@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        $this->command->info('✅ تم إنشاء حساب Super Admin و Admin');

        $this->command->info('✅ تم إنشاء مستخدم Admin افتراضي');
    }
}
