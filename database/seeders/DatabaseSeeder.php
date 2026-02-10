<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        BranchSeeder::class,
        AssetCategorySeeder::class,
        DiplomaSeeder::class,
           EmployeeSeeder::class,
        StudentSeeder::class,
          CashboxSeeder::class,
           AssetSeeder::class,   // ✅ هنا
             // ✅ الدوام والمهام
            WorkShiftSeeder::class,
            EmployeeScheduleSeeder::class,
            AttendanceRecordSeeder::class,
            LeaveRequestSeeder::class,
            TaskSeeder::class,
            ExamSeeder::class,
   // ✅ CRM
        LeadSeeder::class,
         LeadFollowupSeeder::class, // اختياري إذا فصلته
           



             RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,


        
           
    ]);
        // User::factory(10)->create();

      /*  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
