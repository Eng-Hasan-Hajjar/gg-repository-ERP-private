<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentConfirmationController extends Controller
{
    public function __construct()
    {
        // لا تستخدم $this->middleware() إذا كان Controller لا يرث من Illuminate\Routing\Controller
        // عندك صحيح لأنه يرث من App\Http\Controllers\Controller
        $this->middleware(['auth']);
    }

    public function confirm(Student $student)
    {
      //  abort_unless(auth()->user()->can('students.confirm'), 403);

        if (!$student->is_confirmed) {
            $student->update([
                'is_confirmed' => true,
                'confirmed_at' => now(),
                'registration_status' => 'confirmed',
            ]);

            // إنشاء ملف تفصيلي فارغ
            $student->profile()->firstOrCreate(
                ['student_id' => $student->id],
                []
            );
        }

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تثبيت الطالب، ويمكن الآن إضافة الملف التفصيلي والمعلومات الإضافية.');
    }
}
