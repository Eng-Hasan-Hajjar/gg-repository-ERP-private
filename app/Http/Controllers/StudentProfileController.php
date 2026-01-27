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
       // abort_unless(auth()->user()->can('students.extra.update'), 403);

        if (!$student->is_confirmed) {
            abort(403, 'Student not confirmed.');
        }

        $profile = $student->profile()->firstOrCreate(['student_id' => $student->id], []);

        $data = $request->validated();

        // booleans من checkbox
        $data['has_attendance_certificate'] = (bool)($request->boolean('has_attendance_certificate'));
        $data['has_certificate_card']       = (bool)($request->boolean('has_certificate_card'));

        // رفع الملفات (storage/app/public/students/...)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students/photos', 'public');
            $data['photo_path'] = $path;
        }

        if ($request->hasFile('info_file')) {
            $path = $request->file('info_file')->store('students/info_files', 'public');
            $data['info_file_path'] = $path;
        }

        if ($request->hasFile('identity_file')) {
            $path = $request->file('identity_file')->store('students/identity_files', 'public');
            $data['identity_file_path'] = $path;
        }

        if ($request->hasFile('certificate_pdf')) {
            $path = $request->file('certificate_pdf')->store('students/certificates', 'public');
            $data['certificate_pdf_path'] = $path;
            $data['has_certificate_pdf']  = true;
        }

        // إذا المستخدم أزال تفعيل PDF يدوياً
        if (!$request->hasFile('certificate_pdf') && $request->input('has_certificate_pdf') === '0') {
            $data['has_certificate_pdf'] = false;
            $data['certificate_pdf_path'] = null;
        }

        $profile->update($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث الملف التفصيلي للطالب بنجاح.');
    }
}
