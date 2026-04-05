<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiplomaStoreRequest;
use App\Http\Requests\DiplomaUpdateRequest;
use App\Models\Diploma;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiplomaController extends Controller
{
    public function index(Request $request)
    {
        $q = Diploma::with('branch');

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")
                  ->orWhere('code', 'like', "%$s%")
                  ->orWhere('field', 'like', "%$s%");
            });
        }

        if ($request->filled('is_active')) {
            $q->where('is_active', $request->is_active === '1');
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }

        $branches = Branch::orderBy('name')->get();

        return view('diplomas.index', [
            'diplomas' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => $branches,
        ]);
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('diplomas.create', compact('branches'));
    }

    public function store(DiplomaStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('details_pdf')) {
            $data['details_pdf'] = $request->file('details_pdf')
                ->store('diplomas/pdfs', 'public');
        }

        Diploma::create($data);

        return redirect()->route('diplomas.index')
            ->with('success', 'تمت إضافة الدبلومة بنجاح.');
    }

    public function show(Diploma $diploma)
    {
        return view('diplomas.show', compact('diploma'));
    }

    public function edit(Diploma $diploma)
    {
        $branches = Branch::orderBy('name')->get();
        return view('diplomas.edit', compact('diploma', 'branches'));
    }

    public function update(DiplomaUpdateRequest $request, Diploma $diploma)
    {
        $data = $request->validated();

        if ($request->hasFile('details_pdf')) {
            if ($diploma->details_pdf) {
                Storage::disk('public')->delete($diploma->details_pdf);
            }
            $data['details_pdf'] = $request->file('details_pdf')
                ->store('diplomas/pdfs', 'public');
        }

        if ($request->boolean('remove_pdf') && $diploma->details_pdf) {
            Storage::disk('public')->delete($diploma->details_pdf);
            $data['details_pdf'] = null;
        }

        $diploma->update($data);

        return redirect()->route('diplomas.index')
            ->with('success', 'تم تحديث الدبلومة بنجاح.');
    }

    public function destroy(Diploma $diploma)
    {
        if ($diploma->students()->exists()) {
            return back()->with('error', 'لا يمكن حذف الدبلومة لأنها مرتبطة بطلاب.');
        }

        if ($diploma->details_pdf) {
            Storage::disk('public')->delete($diploma->details_pdf);
        }

        $diploma->delete();

        return redirect()->route('diplomas.index')
            ->with('success', 'تم حذف الدبلومة بنجاح.');
    }

    public function toggle(Diploma $diploma)
    {
        $diploma->update(['is_active' => !$diploma->is_active]);
        return back()->with('success', 'تم تحديث حالة الدبلومة.');
    }
}