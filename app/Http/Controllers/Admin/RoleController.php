<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('users');

        // 🔹 بحث بالاسم أو الوصف
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('label', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            });
        }

        // 🔹 فلتر حسب وجود مستخدمين
        if ($request->filled('has_users')) {
            if ($request->has_users == 'yes') {
                $query->has('users');
            } elseif ($request->has_users == 'no') {
                $query->doesntHave('users');
            }
        }

        $roles = $query->latest()->paginate(12);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create($request->only('name','label','description'));
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact(
            'role',
            'permissions',
            'rolePermissions'
        ));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->withErrors('لا يمكن تعديل سوبر أدمين');
        }

        $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        $role->update($request->only('label','description'));
        $role->permissions()->sync($request->permissions ?? []);




        $before = $role->permissions->pluck('id')->toArray();

$role->permissions()->sync($request->permissions ?? []);

$after = $request->permissions ?? [];

$added = array_diff($after, $before);
$removed = array_diff($before, $after);

foreach ($added as $pid) {
    $p = Permission::find($pid);
    audit_log(
        'permission_added',
        "إضافة صلاحية ({$p->label}) للدور ({$role->label}) عبر الحفظ",
        'Role',
        $role->id
    );
}

foreach ($removed as $pid) {
    $p = Permission::find($pid);
    audit_log(
        'permission_removed',
        "إزالة صلاحية ({$p->label}) من الدور ({$role->label}) عبر الحفظ",
        'Role',
        $role->id
    );
}







        return redirect()->route('admin.roles.index')
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->withErrors('لا يمكن حذف سوبر أدمين');
        }

        if ($role->users()->exists()) {
            return back()->withErrors('لا يمكن حذف دور مرتبط بمستخدمين');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور');
    }











    
/**
 * شاشة تفاصيل الدور
 */
public function show(Role $role)
{
    $role->load(['permissions','users']);

    $permissions = Permission::all()->groupBy('module');

    return view('admin.roles.show', compact('role','permissions'));
}

/**
 * نسخ الدور (Clone)
 */
public function clone(Role $role)
{
    $new = Role::create([
        'name' => $role->name.'_copy_'.time(),
        'label' => $role->label.' (نسخة)',
        'description' => $role->description,
    ]);

    $new->permissions()->sync($role->permissions->pluck('id'));

    return redirect()->route('admin.roles.edit',$new)
        ->with('success','تم نسخ الدور بنجاح — يمكنك تعديله الآن');
}

/**
 * شاشة إسناد مستخدمين للدور
 */
public function users(Role $role)
{
    $users = User::all();
    $roleUsers = $role->users->pluck('id')->toArray();

    return view('admin.roles.users', compact('role','users','roleUsers'));
}

/**
 * إسناد مستخدم واحد للدور
 */
public function attachUser(Request $request, Role $role)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $role->users()->syncWithoutDetaching([$request->user_id]);

    return back()->with('success','تم إسناد المستخدم للدور');
}

/**
 * Toggle سريع للصلاحية (تشغيل/إيقاف)
 */
public function togglePermission(Role $role, Permission $permission)
{
    if ($role->permissions->contains($permission->id)) {

        $role->permissions()->detach($permission->id);

        audit_log(
            'permission_removed',
            "إزالة صلاحية ({$permission->label}) من الدور ({$role->label})",
            'Role',
            $role->id
        );

        $status = 'removed';

    } else {

        $role->permissions()->attach($permission->id);

        audit_log(
            'permission_added',
            "إضافة صلاحية ({$permission->label}) إلى الدور ({$role->label})",
            'Role',
            $role->id
        );

        $status = 'added';
    }

    return response()->json(['status' => $status]);
}










}
