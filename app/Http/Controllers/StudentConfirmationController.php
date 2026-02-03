<?php
// app/Http/Controllers/StudentConfirmationController.php
namespace App\Http\Controllers;

use App\Models\Student;

class StudentConfirmationController extends Controller
{
  public function confirm(Student $student)
  {
    if ($student->is_confirmed) {
      return back()->with('info','الطالب مثبت مسبقاً.');
    }

    $student->update([
      'is_confirmed' => true,
      'confirmed_at' => now(),
      'registration_status' => 'confirmed',
    ]);

    return back()->with('success','تم تثبيت الطالب بنجاح.');
  }
}
