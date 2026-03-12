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


        // ── المستخدمون الذين أرسلتهم ──
        $usersData = [
            // CEO / Super Admins
            [
                'email' => 'Ceo@nama2academy.com',
                'name'  => 'أ. محمود حسن',
                'roles' => ['super_admin', 'ceo'],
            ],
            [
                'email' => 'development@nama2academy.com',
                'name'  => 'آ. رغد',
                'roles' => ['super_admin'],
            ],
            [
                'email' => 'Management@nama2academy.com',
                'name'  => 'زينة',
                'roles' => ['super_admin'],
            ],

            // Admins عامة / فرعية
            [
                'email' => 'Bursa@nama2academy.com',
                'name'  => 'ناهد جانات',
                'roles' => ['admin_general'],
            ],
            [
                'email' => 'Istanbul@nama2academy.com',
                'name'  => 'آ.مروة',
                'roles' => ['admin_general', 'branch_manager'],
            ],
            [
                'email' => 'Mersin@nama2academy.com',
                'name'  => 'آ. ريما',
                'roles' => ['admin_general', 'branch_manager'],
            ],
            [
                'email' => 'Kilis@nama2academy.com',
                'name'  => 'آ. حليمة',
                'roles' => ['admin_general', 'branch_manager'],
            ],
            [
                'email' => 'mohammdmasriii@gmail.com',
                'name'  => 'محمد مصري',
                'roles' => ['admin_general'],
            ],

            // ── مستخدم افتراضي موجود سابقًا (تحديث فقط) ──
            [
                'email' => 'eng.hasan.hajjar@gmail.com',
                'name'  => 'System Admin',
                'roles' => ['super_admin'],
            ],
            [
                'email' => 'eng.hasan.hajjar2@gmail.com',
                'name'  => 'Super Admin',
                'roles' => ['super_admin'],
            ],
        ];

        foreach ($usersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('123456789'),
                    'email_verified_at' => now(),
                ]
            );

            // جلب الأدوار من قاعدة البيانات وربطها
            $roleIds = Role::whereIn('name', $data['roles'])->pluck('id')->toArray();
            $user->roles()->syncWithoutDetaching($roleIds);
        }

        $this->command->info('تم إنشاء/تحديث ' . count($usersData) . ' مستخدمين مع أدوارهم بنجاح.');
        $this->command->info('جميع كلمات المرور الافتراضية: 123456789  ← غيّرها فورًا بعد الاستخدام!');


        
    }
}
