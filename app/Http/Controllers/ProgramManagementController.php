<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Models\ProgramManagement;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\UpdateProgramManagementRequest;

class ProgramManagementController extends Controller
{
    // ══════════════════════════════════════════
    // قائمة البرامج — سجل واحد لكل دبلومة فقط
    // ══════════════════════════════════════════
    /*
    public function index(Request $request)
    {
        $query = ProgramManagement::with('diploma')
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MIN(id)')
                    ->from('program_managements')
                    ->groupBy('diploma_id');
            });

        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('manager_id', auth()->id());
        }

        if ($request->filled('type')) {
            $query->whereHas('diploma', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $records = $query->latest()->paginate(15);

        return view('programs_management.index', compact('records'));
    }
*/
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ProgramManagement::with('diploma.branch')
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MIN(id)')
                    ->from('program_managements')
                    ->groupBy('diploma_id');
            });

        // ✅ فلتر الفرع — نفس منطق Diploma GlobalScope
        if (!$user->hasRole('super_admin') && !$user->hasPermission('view_all_diplomas')) {

            $employee = \App\Models\Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->whereNotNull('user_id')
                ->first();

            $branchIds = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id,
            ])->filter()->unique()->values()->all();

            if (!empty($branchIds)) {
                // فلتر عبر الدبلومة المرتبطة
                $query->whereHas(
                    'diploma',
                    fn($q) =>
                    $q->whereIn('branch_id', $branchIds)
                );
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('type')) {
            $query->whereHas(
                'diploma',
                fn($q) =>
                $q->where('type', $request->type)
            );
        }

        $records = $query->latest()->paginate(15);

        return view('programs_management.index', compact('records'));
    }


    // ══════════════════════════════════════════
    // صفحة التعديل — ينشئ سجلاً واحداً فقط
    // ══════════════════════════════════════════
    public function edit(Diploma $diploma)
    {
        // ✅ البحث بـ diploma_id فقط، لكن عند الإنشاء يضع manager_id
        $record = ProgramManagement::where('diploma_id', $diploma->id)->first();

        if (!$record) {
            $record = ProgramManagement::create([
                'diploma_id' => $diploma->id,
                'manager_id' => auth()->id(),
            ]);
        }

        $trainers = Employee::where('type', 'trainer')->get();

        return view('programs_management.edit', compact('record', 'diploma', 'trainers'));
    }

    // ══════════════════════════════════════════
    // حفظ التعديلات
    // ══════════════════════════════════════════
    public function update(UpdateProgramManagementRequest $request, Diploma $diploma)
    {
        $record = ProgramManagement::where('diploma_id', $diploma->id)
            ->firstOrFail();

        $data = $request->validated();

        if ($request->hasFile('details_file')) {
            $path = $request->file('details_file')
                ->store('program_files', 'public');
            $data['details_file'] = $path;
        }

        $booleanFields = [
            'market_study',
            'trainer_assigned',
            'contracts_ready',
            'materials_ready',
            'sessions_uploaded',
            'media_form_sent',
            'direct_ads',
            'content_ready',
            'opening_invitation',
            'opening_snippets',
            'carousel',
            'designs',
            'stories',
            'projects',
            'attendance_certificate',
            'university_certificate',
            'cards_ready',
            'admin_session_1',
            'admin_session_2',
            'admin_session_3',
            'evaluations_done',
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field) ? 1 : 0;
        }

        $record->update($data);

        return back()->with('success', 'تم حفظ البيانات بنجاح');
    }

    // ══════════════════════════════════════════
    // لوحة البرنامج — عرض فقط
    // ══════════════════════════════════════════
    public function show(Diploma $diploma)
    {
        // ✅ نفس المنطق — بحث أولاً، إنشاء إن لم يوجد مع manager_id
        $record = ProgramManagement::where('diploma_id', $diploma->id)->first();

        if (!$record) {
            $record = ProgramManagement::create([
                'diploma_id' => $diploma->id,
                'manager_id' => auth()->id(),
            ]);
        }

        return view('programs_management.show', compact('record', 'diploma'));
    }
}