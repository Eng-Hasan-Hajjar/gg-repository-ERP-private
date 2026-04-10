<?php
use App\Http\Controllers\Reports\ReportsController;
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

use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\MediaRequestController;

//طلبات الإجازات + صفحة موافقات الأدمن
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LocationController;
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






    Route::post(
        'employees/{employee}/create-user',
        [EmployeeController::class, 'createUser']
    )
        ->name('employees.createUser');


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
    Route::get('cashboxes/{cashbox}/transactions', [CashboxTransactionController::class, 'index'])->name('cashboxes.transactions.index');
    Route::get('cashboxes/{cashbox}/transactions/create', [CashboxTransactionController::class, 'create'])->name('cashboxes.transactions.create');
    Route::post('cashboxes/{cashbox}/transactions', [CashboxTransactionController::class, 'store'])->name('cashboxes.transactions.store');

    Route::get('cashboxes/{cashbox}/transactions/{transaction}/edit', [CashboxTransactionController::class, 'edit'])->name('cashboxes.transactions.edit');
    Route::put('cashboxes/{cashbox}/transactions/{transaction}', [CashboxTransactionController::class, 'update'])->name('cashboxes.transactions.update');
    Route::delete('cashboxes/{cashbox}/transactions/{transaction}', [CashboxTransactionController::class, 'destroy'])->name('cashboxes.transactions.destroy');

    // زر سريع: تحويل إلى "مُرحَّل/مدفوع" (Post)
    Route::post('cashboxes/{cashbox}/transactions/{transaction}/post', [CashboxTransactionController::class, 'post'])
        ->name('cashboxes.transactions.post');




});







// Attendance
Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('attendance/today/{employee}', [AttendanceController::class, 'createForToday'])->name('attendance.today.create');

Route::post('attendance/{record}/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
Route::post('attendance/{record}/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

Route::get('attendance/{record}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
Route::put('attendance/{record}', [AttendanceController::class, 'update'])->name('attendance.update');

// Tasks
Route::resource('tasks', TaskController::class);
Route::post('tasks/{task}/quick-status', [TaskController::class, 'quickStatus'])->name('tasks.quickStatus');




Route::resource('leaves', LeaveRequestController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy'])
    ->parameters(['leaves' => 'leave']);
Route::post('leaves/{leave}/approve', [LeaveRequestController::class, 'approve'])->name('leaves.approve');
Route::post('leaves/{leave}/reject', [LeaveRequestController::class, 'reject'])->name('leaves.reject');



//توليد تلقائي لسجلات الأسبوع من employee_schedules

use App\Http\Controllers\AttendanceGeneratorController;

Route::post('attendance/generate-week', [AttendanceGeneratorController::class, 'generateWeek'])->name('attendance.generateWeek');

//Calendar Month View (شهرية) لكل فرع/موظف
Route::get('attendance/calendar', [AttendanceCalendarController::class, 'month'])->name('attendance.calendar');

//تقارير: ساعات/تأخير/غياب لكل موظف خلال فترة

Route::get('attendance/reports', [AttendanceReportController::class, 'index'])->name('attendance.reports');



Route::get('attendance/reports/export-excel', [AttendanceReportController::class, 'exportExcel'])->name('attendance.reports.exportExcel');


Route::get('attendance/reports/export-pdf', [AttendanceReportController::class, 'exportPdf'])->name('attendance.reports.exportPdf');



Route::get('attendance/calendar/export-excel', [AttendanceCalendarController::class, 'exportExcel'])
    ->name('attendance.calendar.exportExcel');

Route::get('attendance/calendar/export-pdf', [AttendanceCalendarController::class, 'exportPdf'])
    ->name('attendance.calendar.exportPdf');







Route::resource('exams', ExamController::class);

// إدخال درجات امتحان
Route::get('exams/{exam}/results', [ExamResultController::class, 'edit'])->name('exams.results.edit');
Route::put('exams/{exam}/results', [ExamResultController::class, 'update'])->name('exams.results.update');






Route::resource('leads', LeadController::class);

Route::post('leads/{lead}/convert', [LeadController::class, 'convertToStudent'])
    ->name('leads.convert');

Route::post('leads/{lead}/followups', [LeadFollowupController::class, 'store'])
    ->name('leads.followups.store');

Route::delete('leads/{lead}/followups/{followup}', [LeadFollowupController::class, 'destroy'])
    ->name('leads.followups.destroy');

// Optional reports
Route::get('crm_lead_reports', [LeadReportController::class, 'index'])->name('crm.reports.index');

Route::get('/crm/reports/pdf', [LeadReportController::class, 'exportPdf'])
    ->name('crm.reports.pdf')
    ->middleware('auth');






Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportsController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportsController::class, 'exportExcel'])->name('reports.excel');
});


use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)
            ->only(['index', 'store']);
    });



use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });



use App\Http\Controllers\Admin\AuditController;

Route::get('/admin/audit-logs', [AuditController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.audit.index2');



Route::get('/audit-center', [AuditController::class, 'index'])
    ->name('admin.audit.index');





Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('roles', RoleController::class);

    // إضافات جديدة:
    //   Route::get('roles/{role}/show', [RoleController::class, 'show'])
    //      ->name('roles.show');

    Route::post('roles/{role}/clone', [RoleController::class, 'clone'])
        ->name('roles.clone');

    Route::get('roles/{role}/users', [RoleController::class, 'users'])
        ->name('roles.users');

    Route::post('roles/{role}/attach-user', [RoleController::class, 'attachUser'])
        ->name('roles.attachUser');

    Route::post(
        'roles/{role}/toggle-permission/{permission}',
        [RoleController::class, 'togglePermission']
    )
        ->name('roles.togglePermission');





});











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



Route::get('/reports/system-alerts', [ReportsController::class, 'alerts'])
    ->name('reports.system.alerts');


Route::get('/reports/charts', [ReportsController::class, 'charts'])
    ->name('reports.charts')
    ->middleware('auth');







use App\Http\Controllers\Admin\AttendanceReportController2;

Route::get('/admin/reports/monthly', [AttendanceReportController2::class, 'monthly'])
    ->name('reports.monthly');
Route::get('/admin/reports/monthly/pdf', [AttendanceReportController2::class, 'monthlyPdf'])
    ->name('reports.monthly.pdf');
Route::get('/admin/reports/attendance/monthly', [AttendanceReportController2::class, 'monthly'])
    ->name('reports.attendance.monthly');
Route::get('/admin/reports/attendance/monthly/pdf', [AttendanceReportController2::class, 'exportPdf'])
    ->name('reports.attendance.monthly.pdf');



Route::post('/financial/pay', [\App\Http\Controllers\FinancialTransactionController::class, 'store'])
    ->name('financial.pay');





Route::prefix('finance')->name('finance.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\FinanceController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/reports/diplomas', [\App\Http\Controllers\FinanceController::class, 'diplomaReport'])
        ->name('reports.diplomas');

    Route::get('/reports/profit', [\App\Http\Controllers\FinanceController::class, 'profitByProgram'])
        ->name('reports.profit');

    Route::get('/reports/daily', [\App\Http\Controllers\FinanceController::class, 'dailyReport'])
        ->name('reports.daily');

});





use App\Http\Controllers\ProgramManagementController;

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/programs-management',
        [ProgramManagementController::class, 'index']
    )
        ->name('programs.management.index');

    Route::get(
        '/programs-management/{diploma}',
        [ProgramManagementController::class, 'edit']
    )
        ->name('programs.management.edit');

    Route::post(
        '/programs-management/{diploma}',
        [ProgramManagementController::class, 'update']
    )
        ->name('programs.management.update');


    Route::get(
        '/programs-management/{diploma}/show',
        [ProgramManagementController::class, 'show']
    )
        ->name('programs.management.show');

});
















use App\Http\Controllers\TaskReportController;

Route::middleware(['auth'])->group(function () {

    Route::get('/reports-task', [TaskReportController::class, 'index'])
        ->name('reports.task.index');

    Route::get('/reports-task/create', [TaskReportController::class, 'create'])
        ->name('reports.task.create');

    Route::post('/reports-task', [TaskReportController::class, 'store'])
        ->name('reports.task.store');

    Route::get('/reports-task/{report}', [TaskReportController::class, 'show'])
        ->name('reports.task.show');

    Route::delete('/reports-task/{report}', [TaskReportController::class, 'destroy'])
        ->name('reports.task.destroy');

});



Route::get(
    '/cashboxes/{cashbox}/transactions/pdf',
    [CashboxTransactionController::class, 'exportPdf']
)->name('cashboxes.transactions.pdf');














use App\Http\Controllers\AlertController;
Route::middleware('auth')->group(function () {

    Route::get('/alerts/navbar', [AlertController::class, 'navbar'])
        ->name('alerts.navbar');

});







Route::post('/transactions/{trx}/post', [FinancialTransactionController::class, 'post'])
    ->name('transactions.post');






use App\Http\Controllers\System\BackupController;

Route::middleware(['auth'])->prefix('system')->name('system.')->group(function () {

    Route::get('/backup', [BackupController::class, 'index'])
        ->name('backup.index');

    Route::get('/backup/download', [BackupController::class, 'download'])
        ->name('backup.download');

    Route::post('/backup/restore', [BackupController::class, 'restore'])
        ->name('backup.restore');

});

use App\Http\Controllers\System\SystemHealthController;

Route::middleware(['auth'])->group(function () {

    Route::get('/system/health', [SystemHealthController::class, 'index'])
        ->name('system.health');

});






Route::middleware(['auth'])->prefix('system')->name('system.')->group(function () {

    Route::get('/backup', [BackupController::class, 'index'])
        ->name('backup.index');

    Route::post('/backup/generate', [BackupController::class, 'generate'])
        ->name('backup.generate');

    Route::get(
        '/backup/download/{file}',
        [BackupController::class, 'downloadFile']
    )
        ->name('backup.download');

});








Route::get('/diplomas/{id}/groups', [LeadController::class, 'getDiplomaGroups']);

//Route::get('/diplomas/groups/{name}', [LeadController::class,'getDiplomaGroups']);




use App\Http\Controllers\PaymentPlanController;

Route::post('/payment-plan/store', [PaymentPlanController::class, 'store'])
    ->name('payment.plan.store');


Route::get(
    '/payment-plan/{plan}/edit',
    [PaymentPlanController::class, 'edit']
)
    ->name('payment.plan.edit');



Route::put('/payment-plan/{plan}', [PaymentPlanController::class, 'update'])
    ->name('payment.plan.update');













use App\Http\Controllers\MediaPublishController;

/*
|--------------------------------------------------------------------------
| Public Media Form (بدون تسجيل دخول)
|--------------------------------------------------------------------------
*/

Route::get(
    '/media-request/public',
    [MediaRequestController::class, 'publicForm']
)->name('media.public.form');

Route::post(
    '/media-request/public',
    [MediaRequestController::class, 'publicStore']
)->name('media.public.store');

Route::get(
    '/media-request/thanks',
    [MediaRequestController::class, 'thanks']
)->name('media.public.thanks');


/*
|--------------------------------------------------------------------------
| Media Panel (تحتاج تسجيل دخول)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // طلبات الميديا
    Route::get('/media-requests', [MediaRequestController::class, 'index'])
        ->name('media.index');

    Route::get('/media-requests/create', [MediaRequestController::class, 'create'])
        ->name('media.create');

    Route::post('/media-requests', [MediaRequestController::class, 'store'])
        ->name('media.store');

    Route::get('/media-requests/{media}', [MediaRequestController::class, 'show'])
        ->name('media.show');

    Route::post('/media-requests/{media}', [MediaRequestController::class, 'update'])
        ->name('media.update');

    // حذف المسودات التجريبية
    Route::delete('/media-requests/cleanup-drafts', [MediaRequestController::class, 'cleanupDrafts'])
        ->name('media.cleanup');

    // قائمة النشر
    Route::get('/media-publish', [MediaPublishController::class, 'index'])
        ->name('media.publish.index');

    Route::get('/media-publish/create', [MediaPublishController::class, 'create'])
        ->name('media.publish.create');

    Route::post('/media-publish', [MediaPublishController::class, 'store'])
        ->name('media.publish.store');

    Route::get('/media-publish/{publish}/edit', [MediaPublishController::class, 'edit'])
        ->name('media.publish.edit');

    Route::put('/media-publish/{publish}', [MediaPublishController::class, 'update'])
        ->name('media.publish.update');

    Route::delete('/media-publish/{publish}', [MediaPublishController::class, 'destroy'])
        ->name('media.publish.destroy');

});


/*
Route::middleware('auth')->group(function () {
    Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
    Route::post('/location/skip',  [LocationController::class, 'skip'])->name('location.skip');
});

*/


Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
Route::post('/location/skip', [LocationController::class, 'skip'])->name('location.skip');



// ═══════════════════════════════════════════════════════
// أضف هذين السطرين في ملف routes/web.php
// داخل مجموعة routes الخاصة بالحضور (attendance)
// ═══════════════════════════════════════════════════════

// استراحة
Route::post('/attendance/{record}/break-start', [App\Http\Controllers\AttendanceController::class, 'breakStart'])
    ->name('attendance.break.start');

Route::post('/attendance/{record}/break-end', [App\Http\Controllers\AttendanceController::class, 'breakEnd'])
    ->name('attendance.break.end');





Route::post('users/{user}/force-logout', [UserController::class, 'forceLogout'])
    ->name('admin.users.forceLogout');

Route::post('users/logout-all', [UserController::class, 'logoutAll'])
    ->name('admin.users.logoutAll');




Route::get('/session/check', function () {

    if (!auth()->check()) {
        return response()->json(['logout' => true]);
    }

    $session = \App\Models\UserSession::where('user_id', auth()->id())
        ->where('session_id', session()->getId())
        ->whereNull('logout_at')
        ->exists();

    return response()->json([
        'logout' => !$session
    ]);

})->middleware('auth');





Route::post('/attendance/{record}/notes', 
[AttendanceController::class,'updateNotes'])
->name('attendance.notes.update');



require __DIR__ . '/auth.php';
