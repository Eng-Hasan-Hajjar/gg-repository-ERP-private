<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetStoreRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $q = Asset::query()->with(['branch','category']);

        if ($request->filled('branch_id')) $q->where('branch_id', $request->branch_id);
        if ($request->filled('condition')) $q->where('condition', $request->condition);
        if ($request->filled('asset_category_id')) $q->where('asset_category_id', $request->asset_category_id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($x) use ($s){
                $x->where('name','like',"%$s%")
                  ->orWhere('asset_tag','like',"%$s%")
                  ->orWhere('serial_number','like',"%$s%");
            });
        }

        return view('assets.index', [
            'assets' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
            'categories' => AssetCategory::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('assets.create', [
            'branches' => Branch::orderBy('name')->get(),
            'categories' => AssetCategory::orderBy('name')->get(),
        ]);
    }

    public function store(AssetStoreRequest $request)
    {
        $data = $request->validated();
        $data['asset_tag'] = $this->generateAssetTag();

        // رفع صورة
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset = Asset::create($data);

        return redirect()->route('assets.show', $asset)->with('success','تم إضافة الأصل بنجاح.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['branch','category']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', [
            'asset' => $asset,
            'branches' => Branch::orderBy('name')->get(),
            'categories' => AssetCategory::orderBy('name')->get(),
        ]);
    }

    public function update(AssetUpdateRequest $request, Asset $asset)
    {
        $data = $request->validated();

        // تحديث صورة (مع حذف القديمة)
        if ($request->hasFile('photo')) {
            if ($asset->photo_path && Storage::disk('public')->exists($asset->photo_path)) {
                Storage::disk('public')->delete($asset->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset->update($data);

        return redirect()->route('assets.show', $asset)->with('success','تم تحديث الأصل.');
    }

    public function destroy(Asset $asset)
    {
        // حذف الصورة إن وجدت
        if ($asset->photo_path && Storage::disk('public')->exists($asset->photo_path)) {
            Storage::disk('public')->delete($asset->photo_path);
        }

        $asset->delete();
        return redirect()->route('assets.index')->with('success','تم حذف الأصل.');
    }

    private function generateAssetTag(): string
    {
        do {
            $tag = 'AST-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Asset::where('asset_tag',$tag)->exists());

        return $tag;
    }
}
