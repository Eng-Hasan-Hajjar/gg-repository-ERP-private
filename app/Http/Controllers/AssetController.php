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
        $q = Asset::query()->with(['branch', 'category']);

        if ($request->filled('branch_id'))
            $q->where('branch_id', $request->branch_id);
        if ($request->filled('condition'))
            $q->where('condition', $request->condition);
        if ($request->filled('asset_category_id'))
            $q->where('asset_category_id', $request->asset_category_id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")
                    ->orWhere('asset_tag', 'like', "%$s%")
                    ->orWhere('serial_number', 'like', "%$s%");
            });
        }

        return view('assets.index', [
            'assets' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
            'categories' => AssetCategory::orderBy('name')->get(),
        ]);
    }
    /*
        public function create()
        {
            return view('assets.create', [
                'branches' => Branch::orderBy('name')->get(),
                'categories' => AssetCategory::orderBy('name')->get(),
            ]);
        }
    */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin') || $user->hasPermission('manage_assets')) {
            $branches = Branch::orderBy('name')->get();
        } else {
            $employee = \App\Models\Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            $branches = Branch::where('id', $employee?->branch_id)->get();
        }


        return view('assets.create', [
            'branches' => $branches,
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

        return redirect()->route('assets.show', $asset)->with('success', 'تم إضافة الأصل بنجاح.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['branch', 'category']);
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

        return redirect()->route('assets.show', $asset)->with('success', 'تم تحديث الأصل.');
    }

    public function destroy(Asset $asset)
    {
        // حذف الصورة إن وجدت
        if ($asset->photo_path && Storage::disk('public')->exists($asset->photo_path)) {
            Storage::disk('public')->delete($asset->photo_path);
        }

        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'تم حذف الأصل.');
    }

    private function generateAssetTag(): string
    {
        do {
            $tag = 'AST-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Asset::where('asset_tag', $tag)->exists());

        return $tag;
    }


    public function exportExcel(Request $request)
    {
        $q = \App\Models\Asset::query()->with(['branch', 'category']);

        if ($request->filled('branch_id'))
            $q->where('branch_id', $request->branch_id);
        if ($request->filled('condition'))
            $q->where('condition', $request->condition);
        if ($request->filled('asset_category_id'))
            $q->where('asset_category_id', $request->asset_category_id);
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(fn($x) => $x->where('name', 'like', "%$s%")
                ->orWhere('asset_tag', 'like', "%$s%")
                ->orWhere('serial_number', 'like', "%$s%"));
        }

        $assets = $q->latest()->get();

        $conditionMap = ['good' => 'جيد', 'maintenance' => 'صيانة', 'out_of_service' => 'خارج الخدمة'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('الأصول');
        $ws->setRightToLeft(true);

        // رأس الجدول
        $headers = ['#', 'التاغ', 'الأصل', 'التصنيف', 'الفرع', 'الحالة', 'الموقع', 'رقم السيريال'];
        foreach ($headers as $i => $h) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $ws->setCellValue("{$col}1", $h);
            $ws->getStyle("{$col}1")->getFont()->setBold(true);
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        // البيانات
        foreach ($assets as $idx => $a) {
            $row = $idx + 2;
            $ws->setCellValue("A{$row}", $a->id);
            $ws->setCellValue("B{$row}", $a->asset_tag);
            $ws->setCellValue("C{$row}", $a->name);
            $ws->setCellValue("D{$row}", $a->category->name ?? '-');
            $ws->setCellValue("E{$row}", $a->branch->name ?? '-');
            $ws->setCellValue("F{$row}", $conditionMap[$a->condition] ?? $a->condition);
            $ws->setCellValue("G{$row}", $a->location ?? '-');
            $ws->setCellValue("H{$row}", $a->serial_number ?? '-');
        }

        $filename = 'assets-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }



}
