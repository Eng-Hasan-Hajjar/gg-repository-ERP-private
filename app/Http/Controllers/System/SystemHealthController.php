<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;

class SystemHealthController extends Controller
{

    public function index()
    {

        $users = User::count();

        $students = Student::count();

        $transactions = DB::table('cashbox_transactions')
            ->whereDate('created_at', now())
            ->count();

        $databaseSize = DB::select("
        SELECT table_schema 'database',
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2)
        'size_mb'
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
        GROUP BY table_schema
        ");

        $size = $databaseSize[0]->size_mb ?? 0;

        $backupPath = storage_path('app/backups');

        $lastBackup = null;

        if (file_exists($backupPath)) {

            $files = glob($backupPath . '/*.sql');

            if ($files) {

                $lastBackup = date("Y-m-d H:i:s", filemtime(max($files)));

            }

        }

        return view('system.health',[
            'users'=>$users,
            'students'=>$students,
            'transactions'=>$transactions,
            'size'=>$size,
            'lastBackup'=>$lastBackup
        ]);

    }
}