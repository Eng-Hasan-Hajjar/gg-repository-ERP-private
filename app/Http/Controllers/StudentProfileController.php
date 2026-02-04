<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentProfileUpdateRequest;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function edit(Student $student)
    {
      //  abort_unless(auth()->user()->can('students.extra.update'), 403);

        if (!$student->is_confirmed) {
            return redirect()
                ->route('students.show', $student)
                ->with('error', 'لا يمكن إضافة الملف التفصيلي قبل تثبيت الطالب.');
        }

        $student->load('profile');

        $profile = $student->profile()->firstOrCreate(['student_id' => $student->id], []);

        return view('students.profile_edit', compact('student','profile'));
    }
public function update(StudentProfileUpdateRequest $request, Student $student)
{
    if (!$student->is_confirmed) abort(403, 'Student not confirmed.');

    $profile = $student->profile()->firstOrCreate(['student_id' => $student->id], []);
    $data = $request->validated();

    $uploadsMap = [
        'photo' => ['col'=>'photo_path', 'dir'=>'students/photos'],
        'info_file' => ['col'=>'info_file_path', 'dir'=>'students/info_files'],
        'identity_file' => ['col'=>'identity_file_path', 'dir'=>'students/identity_files'],
        'attendance_certificate' => ['col'=>'attendance_certificate_path', 'dir'=>'students/attendance_certificates'],
        'certificate_pdf' => ['col'=>'certificate_pdf_path', 'dir'=>'students/certificates/pdf'],
        'certificate_card' => ['col'=>'certificate_card_path', 'dir'=>'students/certificates/card'],
    ];

    foreach ($uploadsMap as $key => $cfg) {
        if ($request->hasFile($key)) {

            $old = $profile->{$cfg['col']} ?? null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            $data[$cfg['col']] = $request->file($key)->store($cfg['dir'], 'public');
        }
    }

    $profile->update($data);

    return redirect()->route('students.show', $student)
        ->with('success', 'تم تحديث الملف التفصيلي للطالب بنجاح.');
}

}
