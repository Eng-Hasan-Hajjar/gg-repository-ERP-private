<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetCategoryRequest;
use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = AssetCategory::query();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where('name','like',"%$s%")
              ->orWhere('code','like',"%$s%");
        }

        return view('asset_categories.index', [
            'items' => $q->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('asset_categories.create');
    }

    public function store(AssetCategoryRequest $request)
    {
        $cat = AssetCategory::create($request->validated());

        return redirect()->route('asset-categories.index')
            ->with('success','تم إضافة تصنيف الأصول بنجاح.');
    }

    public function edit(AssetCategory $asset_category)
    {
        return view('asset_categories.edit', ['item' => $asset_category]);
    }

    public function update(AssetCategoryRequest $request, AssetCategory $asset_category)
    {
        $asset_category->update($request->validated());

        return redirect()->route('asset-categories.index')
            ->with('success','تم تحديث التصنيف بنجاح.');
    }

    public function destroy(AssetCategory $asset_category)
    {
        // ملاحظة: إذا أردت منع الحذف إن كان مرتبط بأصول:
        // abort_if($asset_category->assets()->exists(), 422, 'لا يمكن حذف تصنيف مرتبط بأصول.');

        $asset_category->delete();

        return redirect()->route('asset-categories.index')
            ->with('success','تم حذف التصنيف.');
    }

    // غير مستخدمين ضمن resource لكن لازم يوجدو بسبب resource?
    public function show(AssetCategory $asset_category)
    {
        return redirect()->route('asset-categories.edit', $asset_category);
    }
}
