<?php
// app/Http/Controllers/Admin/VisibilityGroupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisibilityGroup;
use App\Models\Employee;
use Illuminate\Http\Request;

class VisibilityGroupController extends Controller
{
    public function index()
    {
        $groups = VisibilityGroup::withCount('employees')->latest()->get();
        return view('admin.visibility_groups.index', compact('groups'));
    }

    public function create()
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('admin.visibility_groups.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'notes'       => ['nullable', 'string'],
            'managers'    => ['nullable', 'array'],
            'managers.*'  => ['exists:employees,id'],
            'members'     => ['nullable', 'array'],
            'members.*'   => ['exists:employees,id'],
        ]);

        $group = VisibilityGroup::create([
            'name'  => $request->name,
            'notes' => $request->notes,
        ]);

        // ربط المديرين
        $syncData = [];
        foreach ($request->input('managers', []) as $empId) {
            $syncData[$empId] = ['role_in_group' => 'manager'];
        }
        // ربط الأعضاء
        foreach ($request->input('members', []) as $empId) {
            if (!isset($syncData[$empId])) {
                $syncData[$empId] = ['role_in_group' => 'member'];
            }
        }

        $group->employees()->sync($syncData);

        return redirect()->route('admin.visibility-groups.index')
            ->with('success', 'تم إنشاء المجموعة بنجاح.');
    }

    public function edit(VisibilityGroup $visibilityGroup)
    {
        $employees = Employee::orderBy('full_name')->get();
        $managerIds = $visibilityGroup->managers()->pluck('employees.id')->toArray();
        $memberIds  = $visibilityGroup->members()
            ->wherePivot('role_in_group', 'member')
            ->pluck('employees.id')->toArray();

        return view('admin.visibility_groups.edit', compact(
            'visibilityGroup', 'employees', 'managerIds', 'memberIds'
        ));
    }

    public function update(Request $request, VisibilityGroup $visibilityGroup)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'notes'      => ['nullable', 'string'],
            'managers'   => ['nullable', 'array'],
            'managers.*' => ['exists:employees,id'],
            'members'    => ['nullable', 'array'],
            'members.*'  => ['exists:employees,id'],
        ]);

        $visibilityGroup->update([
            'name'  => $request->name,
            'notes' => $request->notes,
        ]);

        $syncData = [];
        foreach ($request->input('managers', []) as $empId) {
            $syncData[$empId] = ['role_in_group' => 'manager'];
        }
        foreach ($request->input('members', []) as $empId) {
            if (!isset($syncData[$empId])) {
                $syncData[$empId] = ['role_in_group' => 'member'];
            }
        }

        $visibilityGroup->employees()->sync($syncData);

        return redirect()->route('admin.visibility-groups.index')
            ->with('success', 'تم تحديث المجموعة بنجاح.');
    }

    public function destroy(VisibilityGroup $visibilityGroup)
    {
        $visibilityGroup->delete();
        return back()->with('success', 'تم حذف المجموعة.');
    }
}