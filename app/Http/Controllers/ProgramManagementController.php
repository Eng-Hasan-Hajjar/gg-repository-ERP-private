<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Models\ProgramManagement;
use Illuminate\Http\Request;

class ProgramManagementController extends Controller
{
    public function index()
    {
        $query = ProgramManagement::with('diploma');

        // مدير البرنامج يرى برامجه فقط
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('manager_id', auth()->id());
        }

        $records = $query->latest()->paginate(15);

        return view('programs_management.index', compact('records'));
    }

    public function edit(Diploma $diploma)
    {
        $record = ProgramManagement::firstOrCreate(
            [
                'diploma_id' => $diploma->id,
                'manager_id' => auth()->id(),
            ]
        );

        return view('programs_management.edit', compact('record','diploma'));
    }

public function update(Request $request, Diploma $diploma)
{
    $record = ProgramManagement::where('diploma_id',$diploma->id)
        ->where('manager_id',auth()->id())
        ->firstOrFail();

    $data = $request->all();

    // جميع الحقول البوليانية
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

    return view('programs_management.show', compact('record','diploma'));
}



}