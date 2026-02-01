<?php

namespace Database\Seeders;

use App\Models\User;
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

        $this->command->info('✅ تم إنشاء مستخدم Admin افتراضي');
    }
}
