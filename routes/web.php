<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentConfirmationController;
use App\Http\Controllers\StudentExtraController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DiplomaController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeContractController;
use App\Http\Controllers\EmployeePayoutController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\CashboxController;
use App\Http\Controllers\CashboxTransactionController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\AttendanceCalendarController;
use App\Http\Controllers\EmployeeSchedule;
  use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadFollowupController;
use App\Http\Controllers\LeadReportController;

use App\Http\Controllers\ExamEnrollmentController;

//طلبات الإجازات + صفحة موافقات الأدمن
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TaskController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

*/


use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


















Route::middleware(['auth'])->group(function () {
    Route::resource('students', StudentController::class);

    Route::post('/students/{student}/confirm', [StudentConfirmationController::class, 'confirm'])
        ->name('students.confirm');

    Route::get('/students/{student}/extra', [StudentExtraController::class, 'edit'])
        ->name('students.extra.edit');

    Route::put('/students/{student}/extra', [StudentExtraController::class, 'update'])
        ->name('students.extra.update');


    Route::get('students/{student}/profile', [StudentProfileController::class, 'edit'])
        ->name('students.profile.edit');

    Route::put('students/{student}/profile', [StudentProfileController::class, 'update'])
        ->name('students.profile.update');


    Route::resource('diplomas', DiplomaController::class)->except(['show']);
    Route::patch('diplomas/{diploma}/toggle', [DiplomaController::class, 'toggle'])->name('diplomas.toggle');

    Route::resource('branches', BranchController::class)->except(['show']);





    // HR (Employees/Trainers)
    Route::resource('employees', EmployeeController::class);

    // Nested: Contracts
    Route::get('employees/{employee}/contracts', [EmployeeContractController::class, 'index'])->name('employees.contracts.index');
    Route::get('employees/{employee}/contracts/create', [EmployeeContractController::class, 'create'])->name('employees.contracts.create');
    Route::post('employees/{employee}/contracts', [EmployeeContractController::class, 'store'])->name('employees.contracts.store');
Route::get('employees/{employee}/contracts/{contract}/edit', [EmployeeContractController::class, 'edit'])
  ->name('employees.contracts.edit');

Route::put('employees/{employee}/contracts/{contract}', [EmployeeContractController::class, 'update'])
  ->name('employees.contracts.update');

Route::delete('employees/{employee}/contracts/{contract}', [EmployeeContractController::class, 'destroy'])
  ->name('employees.contracts.destroy');





    // Nested: Payouts (مستحقات)
    Route::get('employees/{employee}/payouts', [EmployeePayoutController::class, 'index'])->name('employees.payouts.index');
    Route::get('employees/{employee}/payouts/create', [EmployeePayoutController::class, 'create'])->name('employees.payouts.create');
    Route::post('employees/{employee}/payouts', [EmployeePayoutController::class, 'store'])->name('employees.payouts.store');

    Route::get('employees/{employee}/payouts/{payout}/edit', [EmployeePayoutController::class, 'edit'])
  ->name('employees.payouts.edit');

Route::put('employees/{employee}/payouts/{payout}', [EmployeePayoutController::class, 'update'])
  ->name('employees.payouts.update');

Route::delete('employees/{employee}/payouts/{payout}', [EmployeePayoutController::class, 'destroy'])
  ->name('employees.payouts.destroy');

  Route::patch('employees/{employee}/payouts/{payout}/mark-paid', [EmployeePayoutController::class, 'markPaid'])
  ->name('employees.payouts.markPaid');






    // Assets
    Route::resource('assets', AssetController::class);

    // Asset Categories
    Route::resource('asset-categories', AssetCategoryController::class);



        
    Route::resource('cashboxes', CashboxController::class);

    // Nested Transactions
    Route::get('cashboxes/{cashbox}/transactions', [CashboxTransactionController::class,'index'])->name('cashboxes.transactions.index');
    Route::get('cashboxes/{cashbox}/transactions/create', [CashboxTransactionController::class,'create'])->name('cashboxes.transactions.create');
    Route::post('cashboxes/{cashbox}/transactions', [CashboxTransactionController::class,'store'])->name('cashboxes.transactions.store');

    Route::get('cashboxes/{cashbox}/transactions/{transaction}/edit', [CashboxTransactionController::class,'edit'])->name('cashboxes.transactions.edit');
    Route::put('cashboxes/{cashbox}/transactions/{transaction}', [CashboxTransactionController::class,'update'])->name('cashboxes.transactions.update');
    Route::delete('cashboxes/{cashbox}/transactions/{transaction}', [CashboxTransactionController::class,'destroy'])->name('cashboxes.transactions.destroy');

    // زر سريع: تحويل إلى "مُرحَّل/مدفوع" (Post)
    Route::post('cashboxes/{cashbox}/transactions/{transaction}/post', [CashboxTransactionController::class,'post'])
        ->name('cashboxes.transactions.post');

    // Quick Post
    Route::post('cashboxes/{cashbox}/transactions/{transaction}/post', [CashboxTransactionController::class,'post'])
        ->name('cashboxes.transactions.post');


    });







// Attendance
Route::get('attendance', [AttendanceController::class,'index'])->name('attendance.index');
Route::get('attendance/today/{employee}', [AttendanceController::class,'createForToday'])->name('attendance.today.create');

Route::post('attendance/{record}/check-in', [AttendanceController::class,'checkIn'])->name('attendance.checkin');
Route::post('attendance/{record}/check-out', [AttendanceController::class,'checkOut'])->name('attendance.checkout');

Route::get('attendance/{record}/edit', [AttendanceController::class,'edit'])->name('attendance.edit');
Route::put('attendance/{record}', [AttendanceController::class,'update'])->name('attendance.update');

// Tasks
Route::resource('tasks', TaskController::class);
Route::post('tasks/{task}/quick-status', [TaskController::class,'quickStatus'])->name('tasks.quickStatus');




Route::resource('leaves', LeaveRequestController::class)
    ->only(['index','create','store','show'])
    ->parameters(['leaves' => 'leave']);
Route::post('leaves/{leave}/approve', [LeaveRequestController::class,'approve'])->name('leaves.approve');
Route::post('leaves/{leave}/reject',  [LeaveRequestController::class,'reject'])->name('leaves.reject');



//توليد تلقائي لسجلات الأسبوع من employee_schedules

    use App\Http\Controllers\AttendanceGeneratorController;

Route::post('attendance/generate-week', [AttendanceGeneratorController::class,'generateWeek'])->name('attendance.generateWeek');

//Calendar Month View (شهرية) لكل فرع/موظف
Route::get('attendance/calendar', [AttendanceCalendarController::class,'month'])->name('attendance.calendar');

//تقارير: ساعات/تأخير/غياب لكل موظف خلال فترة

Route::get('attendance/reports', [AttendanceReportController::class,'index'])->name('attendance.reports');



Route::get('attendance/reports/export-excel', [AttendanceReportController::class,'exportExcel'])->name('attendance.reports.exportExcel');


Route::get('attendance/reports/export-pdf', [AttendanceReportController::class,'exportPdf'])->name('attendance.reports.exportPdf');



Route::get('attendance/calendar/export-excel', [AttendanceCalendarController::class,'exportExcel'])
  ->name('attendance.calendar.exportExcel');

Route::get('attendance/calendar/export-pdf', [AttendanceCalendarController::class,'exportPdf'])
  ->name('attendance.calendar.exportPdf');







Route::resource('exams', ExamController::class);

// إدخال درجات امتحان
Route::get('exams/{exam}/results', [ExamResultController::class,'edit'])->name('exams.results.edit');
Route::put('exams/{exam}/results', [ExamResultController::class,'update'])->name('exams.results.update');

// سجل امتحانات طالب
Route::get('students/{student}/exams', [ExamResultController::class,'studentTranscript'])->name('students.exams');


use App\Http\Controllers\ExamComponentController;
use App\Http\Controllers\ExamMarksController;

// Components management
Route::get('exams/{exam}/components', [ExamComponentController::class,'index'])->name('exams.components.index');
Route::post('exams/{exam}/components', [ExamComponentController::class,'store'])->name('exams.components.store');
Route::delete('exams/{exam}/components/{component}', [ExamComponentController::class,'destroy'])->name('exams.components.destroy');

// Marks entry (dynamic components)
Route::get('exams/{exam}/marks', [ExamMarksController::class,'edit'])->name('exams.marks.edit');
Route::put('exams/{exam}/marks', [ExamMarksController::class,'update'])->name('exams.marks.update');

 Route::get('exams/{exam}/marks/student/{student}', function (\App\Models\Exam $exam, \App\Models\Student $student) {
    return redirect()->route('exams.marks.edit', $exam).'?student_id='.$student->id;
})->name('exams.marks.student');



Route::get('exams/{exam}/students', [ExamEnrollmentController::class,'edit'])
    ->name('exams.students.edit');

Route::put('exams/{exam}/students', [ExamEnrollmentController::class,'update'])
    ->name('exams.students.update');







    

    Route::resource('leads', LeadController::class);

    Route::post('leads/{lead}/convert', [LeadController::class,'convertToStudent'])
      ->name('leads.convert');

    Route::post('leads/{lead}/followups', [LeadFollowupController::class,'store'])
      ->name('leads.followups.store');

    Route::delete('leads/{lead}/followups/{followup}', [LeadFollowupController::class,'destroy'])
      ->name('leads.followups.destroy');

    // Optional reports
    Route::get('crm_lead_reports', [LeadReportController::class,'index'])->name('crm.reports.index');
 


    use App\Http\Controllers\Reports\ReportsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportsController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportsController::class, 'exportExcel'])->name('reports.excel');
});


use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::middleware(['auth', 'permission:manage_roles'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)
            ->only(['index', 'store']);
    });



    use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'permission:manage_roles'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });



use App\Http\Controllers\Admin\AuditController;

Route::get('/admin/audit-logs', [AuditController::class, 'index'])
    ->middleware(['auth','permission:manage_roles'])
    ->name('admin.audit.index');








Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

    // إضافات جديدة:
    Route::get('roles/{role}/show', [RoleController::class, 'show'])
        ->name('roles.show');

    Route::post('roles/{role}/clone', [RoleController::class, 'clone'])
        ->name('roles.clone');

    Route::get('roles/{role}/users', [RoleController::class, 'users'])
        ->name('roles.users');

    Route::post('roles/{role}/attach-user', [RoleController::class, 'attachUser'])
        ->name('roles.attachUser');

    Route::post('roles/{role}/toggle-permission/{permission}', 
        [RoleController::class, 'togglePermission'])
        ->name('roles.togglePermission');





});




Route::get('/audit-center', [\App\Http\Controllers\Admin\AuditController::class, 'index'])
    ->name('admin.audit.index');







Route::get('/reports/executive', [ReportsController::class, 'executive'])
    ->name('reports.executive')
    ->middleware('auth');

Route::get('/reports/branches-map', [ReportsController::class, 'branchesMap'])
    ->name('reports.branches.map')
    ->middleware('auth');





   /* Route::get('/reports/students-growth', function(){
   return view('reports.students-growth');
})->name('reports.students.growth');*/

Route::get('/reports/students-growth', [ReportsController::class, 'studentsGrowth'])
    ->name('reports.students.growth');



Route::get('/reports/revenue-branches', [ReportsController::class, 'revenuePerBranch'])
    ->name('reports.revenue.branches');



Route::get('/reports/system-alerts', [ReportsController::class,'alerts'])
    ->name('reports.system.alerts');


   Route::get('/reports/charts', [ReportsController::class, 'charts'])
    ->name('reports.charts')
    ->middleware('auth');




   use App\Http\Controllers\AlertController;
    Route::middleware('auth')->group(function () {

        Route::get('/alerts/navbar', [AlertController::class, 'navbar'])
            ->name('alerts.navbar');

    });




require __DIR__.'/auth.php';
