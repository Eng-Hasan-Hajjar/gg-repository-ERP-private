<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ── super_admin: كل الصلاحيات ──
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::all()->pluck('id'));
        }

        // ── manager_student_affairs: يرى كل الطلاب ──
        $managerAll = Role::where('name', 'manager_student_affairs')->first();
        if ($managerAll) {
            $managerAll->permissions()->sync(
                Permission::whereIn('name', [
                    'view_dashboard',
                    'view_students',
                    'create_students',
                    'edit_students',
                    'delete_students',
                    'view_all_students',      // ✅ يرى الكل
                    'view_student_financials',
                    'view_exams',
                    'view_branches',
                    'view_diplomas',
                    'view_leads',
                    'view_calendar',
                    'create_events',
                ])->pluck('id')
            );
        }

        // ── manager_branch_students: يرى طلاب فرعه ──
        $managerBranch = Role::where('name', 'manager_branch_students')->first();
        if ($managerBranch) {
            $managerBranch->permissions()->sync(
                Permission::whereIn('name', [
                    'view_dashboard',
                    'view_students',
                    'create_students',
                    'edit_students',
                    'view_branch_students',   // ✅ يرى فرعه فقط
                    'view_student_financials',
                    'view_exams',
                    'view_branches',
                    'view_diplomas',
                    'view_calendar',
                ])->pluck('id')
            );
        }

        // ── staff_student_affairs: موظف عادي ──
        $staff = Role::where('name', 'staff_student_affairs')->first();
        if ($staff) {
            $staff->permissions()->sync(
                Permission::whereIn('name', [
                    'view_dashboard',
                    'view_students',
                    'create_students',
                    'edit_students',
                    // لا يوجد view_all_students أو view_branch_students
                    // إذاً سيرى فقط ما أضافه هو
                    'view_exams',
                    'view_calendar',
                ])->pluck('id')
            );
        }

        // ── admin_general ──
        $admin = Role::where('name', 'admin_general')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::all()->pluck('id'));
        }


        // manager_student_affairs أو أي مدير يرى الكل
        $managerAll = Role::where('name', 'manager_student_affairs')->first();
        if ($managerAll) {
            $managerAll->permissions()->syncWithoutDetaching(
                Permission::whereIn('name', ['view_all_diplomas'])->pluck('id')
            );
        }

        // branch_manager → يرى فرعه فقط
        $branchManager = Role::where('name', 'branch_manager')->first();
        if ($branchManager) {
            $branchManager->permissions()->syncWithoutDetaching(
                Permission::whereIn('name', ['view_branch_diplomas', 'view_diplomas'])->pluck('id')
            );
        }
    }
}