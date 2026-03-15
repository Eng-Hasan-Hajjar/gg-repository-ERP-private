<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{

    /*  public function index()
      {
          return view('system.backup');
      }*/


    public function index()
    {

        $path = storage_path('app/backups');

        $files = [];

        if (file_exists($path)) {

            $allFiles = collect(File::files($path))
                ->filter(function ($file) {
                    return $file->getSize() > 0;
                });

            foreach ($allFiles as $file) {

                $files[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                    'date' => date('Y-m-d H:i', $file->getMTime())
                ];

            }

        }

        /* ترتيب الأحدث أولاً */

        $files = collect($files)->sortByDesc('date');

        return view('system.backup.index', compact('files'));

    }




    public function download()
    {
        $config = config('database.connections.mysql');
        $database = $config['database'] ?? 'laravel11_auth';
        $user = $config['username'] ?? 'root';
        $pass = $config['password'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';

        $filename = 'full_backup_' . date('Y_m_d_H_i_s') . '.sql';
        $path = storage_path('app/backups');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fullPath = $path . '/' . $filename;

        // استخدم المسار الكامل (ضروري على Windows/XAMPP)
        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';  // ← تأكد من المسار ده صحيح

        // خيارات لتصدير كامل بدون استثناء جداول
        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s --single-transaction --quick --lock-tables=false --routines --triggers --events --default-character-set=utf8mb4 --no-tablespaces --result-file=%s %s 2>&1',
            $mysqldump,
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($host),
            escapeshellarg($fullPath),
            escapeshellarg($database)
        );

        exec($command, $output, $return_var);

        \Log::info('Backup command executed', [
            'command' => $command,
            'return_var' => $return_var,
            'output' => $output,
        ]);

        if ($return_var !== 0) {
            return back()->with('error', 'فشل إنشاء النسخة الاحتياطية: ' . implode(' | ', $output));
        }

        if (!file_exists($fullPath) || filesize($fullPath) < 1000) {  // تحقق إضافي
            return back()->with('error', 'تم إنشاء ملف فارغ أو تالف');
        }

        return response()->download($fullPath, $filename)->deleteFileAfterSend(true);
    }




    public function restore(Request $request)
    {



    try {
    DB::table('sessions')->exists();
} catch (\Exception $e) {
    if (str_contains($e->getMessage(), 'sessions') && str_contains($e->getMessage(), '1146')) {
        DB::statement("
            CREATE TABLE `sessions` (
                `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `user_id` bigint unsigned DEFAULT NULL,
                `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                `last_activity` int NOT NULL,
                PRIMARY KEY (`id`),
                KEY `sessions_user_id_index` (`user_id`),
                KEY `sessions_last_activity_index` (`last_activity`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }
}




    session()->save();          // حفظ الجلسة الحالية أولاً
session()->forget('error'); // اختياري: تنظيف أي رسائل خطأ قديمة



        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt'
        ]);

        $file = $request->file('backup_file');
        $fullPath = $file->getRealPath();   // نستخدم المسار المباشر

        $config = config('database.connections.mysql');

        try {
            $pdo = new \PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            // 1. تعطيل الـ foreign keys مؤقتًا
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            // 2. حذف كل الجداول (أضمن طريقة)
            $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
            }

            // 3. قراءة وتنفيذ الملف سطر بسطر
            $sql = file_get_contents($fullPath);
            $statements = array_filter(array_map('trim', explode(';', $sql)));

            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    $pdo->exec($stmt);
                }
            }

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            // بعد الـ foreach statements وقبل return success
            $pdo->exec("
    DROP TABLE IF EXISTS `sessions`;
    CREATE TABLE `sessions` (
        `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `user_id` bigint unsigned DEFAULT NULL,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `last_activity` int NOT NULL,
        PRIMARY KEY (`id`),
        KEY `sessions_user_id_index` (`user_id`),
        KEY `sessions_last_activity_index` (`last_activity`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");
           // return back()->with('success', 'تم استرجاع النسخة الاحتياطية بنجاح (تم حذف الجداول القديمة أولاً)');
return redirect()->route('system.backup.index')
                 ->with('success', 'تم استرجاع النسخة الاحتياطية بنجاح');
        } catch (\Exception $e) {
            \Log::error('Restore failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'فشل الاسترجاع: ' . $e->getMessage());
        }
    }













    public function downloadFile($file)
    {

        $path = storage_path('app/backups/' . $file);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);

    }


}