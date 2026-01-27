<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $q = Branch::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")
                  ->orWhere('code', 'like', "%$s%");
            });
        }

        return view('branches.index', [
            'branches' => $q->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(BranchStoreRequest $request)
    {
        Branch::create($request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'تمت إضافة الفرع بنجاح.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()
            ->route('branches.index')
            ->with('success', 'تم تحديث الفرع بنجاح.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->students()->exists()) {
            return back()->with('error', 'لا يمكن حذف الفرع لأنه مرتبط بطلاب.');
        }

        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'تم حذف الفرع بنجاح.');
    }
}
