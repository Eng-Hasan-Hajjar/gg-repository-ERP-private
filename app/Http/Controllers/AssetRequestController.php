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
    /*
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
*/
    // ── نموذج الطلب الجديد ──
    public function create()
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)->first();

        $branches = $user->hasRole('super_admin') || $user->hasPermission('manage_assets')
            ? Branch::orderBy('name')->get()
            : Branch::where('id', $employee?->branch_id)->get();

        $assets = Asset::orderBy('name')->get();

        return view('asset_requests.create', compact('branches', 'assets'));
    }

    // ── حفظ الطلب ──
// ── قائمة الطلبات ──
    public function index(Request $request)
    {
        $user = auth()->user();
        $q = AssetRequest::query()->with(['user', 'branch', 'asset', 'reviewer']);

        if (!$user->hasRole('super_admin') && !$user->hasPermission('manage_assets')) {
            $q->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }
        if ($request->filled('priority')) {
            $q->where('priority', $request->priority);
        }

        // ترتيب: العاجل أولاً ثم الأحدث
        $q->orderByRaw("FIELD(priority, 'urgent', 'normal', 'low')")
            ->orderByDesc('created_at');

        return view('asset_requests.index', [
            'requests' => $q->paginate(20)->withQueryString(),
        ]);
    }

    // ── حفظ الطلب ──
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:purchase,repair,transfer'],
            'priority' => ['required', 'in:low,normal,urgent'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'asset_id' => ['nullable', 'exists:assets,id'],


            'from_branch_id' => ['nullable', 'exists:branches,id', 'required_if:type,transfer'],
            'to_branch_id' => ['nullable', 'exists:branches,id', 'required_if:type,transfer', 'different:from_branch_id'],


        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        AssetRequest::create($data);

        $user = auth()->user();

        if ($user->hasRole('super_admin') || $user->hasPermission('manage_assets')) {
            return redirect()->route('asset-requests.index')
                ->with('success', 'تم تقديم الطلب بنجاح.');
        }

        return redirect()->route('dashboard')
            ->with('asset_request_success', 'تم تقديم طلبك بنجاح، في انتظار موافقة المدير.');
    }

    public function show(AssetRequest $assetRequest)
    {
        $assetRequest->load(['user', 'branch', 'asset', 'reviewer', 'fromBranch', 'toBranch']);
        return view('asset_requests.show', compact('assetRequest'));
    }




    // ── نموذج تعديل الطلب ──
public function edit(AssetRequest $assetRequest)
{
    abort_if(
        $assetRequest->user_id !== auth()->id() && !auth()->user()->hasPermission('manage_assets'),
        403
    );
    abort_if($assetRequest->status !== 'pending', 422, 'لا يمكن تعديل طلب تمت مراجعته.');

    $user = auth()->user();
    $employee = \App\Models\Employee::withoutGlobalScopes()
        ->where('user_id', $user->id)->first();

    $branches = $user->hasRole('super_admin') || $user->hasPermission('manage_assets')
        ? Branch::orderBy('name')->get()
        : Branch::where('id', $employee?->branch_id)->get();

    $assets = Asset::orderBy('name')->get();

    return view('asset_requests.edit', compact('assetRequest', 'branches', 'assets'));
}

// ── حفظ التعديل ──
public function update(Request $request, AssetRequest $assetRequest)
{
    abort_if(
        $assetRequest->user_id !== auth()->id() && !auth()->user()->hasPermission('manage_assets'),
        403
    );
    abort_if($assetRequest->status !== 'pending', 422, 'لا يمكن تعديل طلب تمت مراجعته.');

    $data = $request->validate([
        'type'        => ['required', 'in:purchase,repair,transfer'],
        'priority'    => ['required', 'in:low,normal,urgent'],
        'title'       => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:2000'],
        'branch_id'   => ['nullable', 'exists:branches,id'],
        'asset_id'    => ['nullable', 'exists:assets,id'],

        'from_branch_id' => ['nullable', 'exists:branches,id', 'required_if:type,transfer'],
        'to_branch_id'   => ['nullable', 'exists:branches,id', 'required_if:type,transfer', 'different:from_branch_id'],
    ]);

    // ✅ إذا غيّر النوع من "نقل" لشيء آخر، نفضّي حقول الفروع
    if ($data['type'] !== 'transfer') {
        $data['from_branch_id'] = null;
        $data['to_branch_id']   = null;
    }

    $assetRequest->update($data);

    return redirect()->route('asset-requests.index')
        ->with('success', 'تم تعديل الطلب بنجاح.');
}


    // ── قبول الطلب ──




    public function approve(Request $request, AssetRequest $assetRequest)
    {
        // ✅ بدلاً من إنشاء أصل تلقائياً — فقط غيّر الحالة إلى approved
        $assetRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'تم قبول الطلب بنجاح. يمكنك الآن ترحيله إلى الأصول.');
    }

    // ✅ دالة جديدة — الترحيل اليدوي إلى أصل
    public function transferToAsset(Request $request, AssetRequest $assetRequest)
    {
        abort_unless($assetRequest->status === 'approved', 403, 'يجب قبول الطلب أولاً.');
        abort_unless(
            auth()->user()->hasPermission('manage_assets') || auth()->user()->hasRole('super_admin'),
            403
        );

        $validated = $request->validate([
            'asset_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',  // ✅ purchase_cost
            'currency' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);

        \DB::transaction(function () use ($assetRequest, $validated) {
            do {
                $tag = 'AST-' . now()->format('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            } while (\App\Models\Asset::where('asset_tag', $tag)->exists());

            $asset = \App\Models\Asset::create([
                'asset_tag' => $tag,
                'name' => $validated['asset_name'],
                'asset_category_id' => null,
                'branch_id' => $assetRequest->branch_id,
                'serial_number' => $validated['serial_number'] ?? null,
                'purchase_date' => $validated['purchase_date'] ?? null,
                'purchase_cost' => $validated['purchase_cost'] ?? null,  // ✅
                'currency' => $validated['currency'] ?? 'USD',
                'condition' => 'good',
                'description' => $validated['notes'] ?? $assetRequest->description,
            ]);

            $assetRequest->update([
                'status' => 'transferred',
                'transferred_to' => $asset->id,
                'transferred_at' => now(),
                'transferred_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'تم ترحيل الطلب إلى الأصول بنجاح.');
    }



    // ── رفض الطلب ──
    public function reject(Request $request, AssetRequest $assetRequest)
    {
        $request->validate([
            'manager_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $assetRequest->update([
            'status' => 'rejected',
            'manager_notes' => $request->manager_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
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