<?php
// app/Http/Controllers/AssetRequestController.php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class AssetRequestController extends Controller
{
    // ── قائمة الطلبات ──
    public function index(Request $request)
    {
        $user = auth()->user();
        $q    = AssetRequest::query()->with(['user', 'branch', 'asset', 'reviewer']);

        // مدير اللوجستيات أو super_admin يرى الكل
        if (!$user->hasRole('super_admin') && !$user->hasPermission('manage_assets')) {
            $q->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        return view('asset_requests.index', [
            'requests' => $q->latest()->paginate(20)->withQueryString(),
        ]);
    }

    // ── نموذج الطلب الجديد ──
    public function create()
    {
        $user     = auth()->user();
        $employee = \App\Models\Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)->first();

        $branches = $user->hasRole('super_admin') || $user->hasPermission('manage_assets')
            ? Branch::orderBy('name')->get()
            : Branch::where('id', $employee?->branch_id)->get();

        $assets = Asset::orderBy('name')->get();

        return view('asset_requests.create', compact('branches', 'assets'));
    }

    // ── حفظ الطلب ──
public function store(Request $request)
{
    $data = $request->validate([
        'type'        => ['required', 'in:purchase,repair'],
        'title'       => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:2000'],
        'branch_id'   => ['nullable', 'exists:branches,id'],
        'asset_id'    => ['nullable', 'exists:assets,id'],
    ]);

    $data['user_id'] = auth()->id();
    $data['status']  = 'pending';

    AssetRequest::create($data);

    // ✅ إذا كان مديراً يُعاد توجيهه لإدارة الطلبات
    // وإذا كان موظفاً عادياً يُعاد توجيهه للداشبورد
    $user = auth()->user();

    if ($user->hasRole('super_admin') || $user->hasPermission('manage_assets')) {
        return redirect()->route('asset-requests.index')
            ->with('success', 'تم تقديم الطلب بنجاح.');
    }

    // ✅ موظف عادي → داشبورد مع رسالة JavaScript
    return redirect()->route('dashboard')
        ->with('asset_request_success', 'تم تقديم طلبك بنجاح، في انتظار موافقة المدير.');
}
    // ── قبول الطلب ──
   public function approve(AssetRequest $assetRequest)
{
    $assetRequest->update([
        'status'      => 'approved',
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    // ✅ إذا كان طلب شراء → أنشئ asset تلقائياً
    if ($assetRequest->type === 'purchase') {

        // توليد asset_tag فريد
        do {
            $tag = 'AST-' . now()->format('Y') . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6));
        } while (\App\Models\Asset::where('asset_tag', $tag)->exists());

        \App\Models\Asset::create([
            'asset_tag'   => $tag,
            'name'        => $assetRequest->title,
            'description' => $assetRequest->description,
            'branch_id'   => $assetRequest->branch_id,
            'condition'   => 'good',
            'currency'    => 'USD',
        ]);
    }

    return back()->with('success', 'تم قبول الطلب وترحيله إلى اللوجستيات.');
}

    // ── رفض الطلب ──
    public function reject(Request $request, AssetRequest $assetRequest)
    {
        $request->validate([
            'manager_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $assetRequest->update([
            'status'        => 'rejected',
            'manager_notes' => $request->manager_notes,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        return back()->with('success', 'تم رفض الطلب.');
    }

    // ── حذف الطلب (صاحبه فقط إذا كان pending) ──
    public function destroy(AssetRequest $assetRequest)
    {
        abort_if(
            $assetRequest->user_id !== auth()->id() && !auth()->user()->hasPermission('manage_assets'),
            403
        );
        abort_if($assetRequest->status !== 'pending', 422, 'لا يمكن حذف طلب تمت مراجعته.');

        $assetRequest->delete();
        return back()->with('success', 'تم حذف الطلب.');
    }
}