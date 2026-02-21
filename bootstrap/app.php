<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Events;
use Illuminate\Console\Scheduling\Schedule;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'otp' => \App\Http\Middleware\EnsureEmailOtpVerified::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);
        // ✅ تسجيله داخل مجموعة WEB (المهم)
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserActivity::class,
            \App\Http\Middleware\AuthorizeByRoute::class,
            \App\Http\Middleware\AutoLogout::class,
        ]);


    })
    ->withSchedule(function (Schedule $schedule) {

        // ✅ تسجيل خروج إجباري يومياً 12 ليلاً
        $schedule->command('sessions:force-logout')->dailyAt('00:00');
        // للتجربة فقط
        // $schedule->command('sessions:force-logout')->everyMinute();
    
    })
    ->withEvents(discover: true)


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
