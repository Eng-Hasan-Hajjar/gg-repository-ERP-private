<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiplomaStoreRequest;
use App\Http\Requests\DiplomaUpdateRequest;
use App\Models\Diploma;
use Illuminate\Http\Request;

class DiplomaController extends Controller
{
    public function index(Request $request)
    {
        $q = Diploma::query();

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

        return view('diplomas.index', [
            'diplomas' => $q->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('diplomas.create');
    }

    public function store(DiplomaStoreRequest $request)
    {
        Diploma::create($request->validated());

        return redirect()
            ->route('diplomas.index')
            ->with('success', 'تمت إضافة الدبلومة بنجاح.');
    }

    public function edit(Diploma $diploma)
    {
        return view('diplomas.edit', compact('diploma'));
    }

    public function update(DiplomaUpdateRequest $request, Diploma $diploma)
    {
        $diploma->update($request->validated());

        return redirect()
            ->route('diplomas.index')
            ->with('success', 'تم تحديث الدبلومة بنجاح.');
    }

    public function destroy(Diploma $diploma)
    {
        // حماية بسيطة: إذا عليها طلاب لا نحذف
        if ($diploma->students()->exists()) {
            return back()->with('error', 'لا يمكن حذف الدبلومة لأنها مرتبطة بطلاب.');
        }

        $diploma->delete();

        return redirect()
            ->route('diplomas.index')
            ->with('success', 'تم حذف الدبلومة بنجاح.');
    }

    public function toggle(Diploma $diploma)
    {
        $diploma->update(['is_active' => !$diploma->is_active]);

        return back()->with('success', 'تم تحديث حالة الدبلومة.');
    }
}
