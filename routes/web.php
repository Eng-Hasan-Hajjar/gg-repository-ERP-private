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


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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
















require __DIR__.'/auth.php';
