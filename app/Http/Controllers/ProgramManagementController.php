<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Models\ProgramManagement;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\UpdateProgramManagementRequest;

class ProgramManagementController extends Controller
{
public function index(Request $request)
{
    $query = ProgramManagement::with('diploma');

    // مدير البرنامج يرى برامجه فقط
    if (!auth()->user()->hasRole('super_admin')) {
        $query->where('manager_id', auth()->id());
    }

    // فلترة حسب نوع الدبلومة
    if ($request->filled('type')) {
        $query->whereHas('diploma', function ($q) use ($request) {
            $q->where('type', $request->type);
        });
    }

    $records = $query->latest()->paginate(15);

    return view('programs_management.index', compact('records'));
}


    public function edit(Diploma $diploma)
    {
        $record = ProgramManagement::firstOrCreate([
            'diploma_id' => $diploma->id,
            'manager_id' => auth()->id(),
        ]);

        $trainers = Employee::where('type', 'trainer')->get();

        return view(
            'programs_management.edit',
            compact('record', 'diploma', 'trainers')
        );
    }

    public function update(UpdateProgramManagementRequest  $request, Diploma $diploma)
    {
       $record = ProgramManagement::where('diploma_id',$diploma->id)
        ->where('manager_id',auth()->id())
        ->firstOrFail();

    $data = $request->validated();

    if ($request->hasFile('details_file')) {

        $path = $request->file('details_file')
            ->store('program_files','public');

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
        'evaluations_done'
    ];

    foreach ($booleanFields as $field) {
        $data[$field] = $request->has($field) ? 1 : 0;
    }

    $record->update($data);

    return back()->with('success','تم حفظ البيانات بنجاح');
    }


    public function show(Diploma $diploma)
    {
        $record = ProgramManagement::firstOrCreate(
            [
                'diploma_id' => $diploma->id,
                'manager_id' => auth()->id(),
            ]
        );

        return view('programs_management.show', compact('record', 'diploma'));
    }



}