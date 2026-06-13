<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use App\Models\UserSession;

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

      // ════════════════════════════════════
    // ✅ صفحة مراقبة الموارد
    // ════════════════════════════════════
    public function resources()
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403);

        return view('system.resources', $this->collectResourceData());
    }

    // ✅ بيانات لحظية (AJAX) لتحديث الكروت بدون إعادة تحميل
    public function resourcesData()
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403);

        return response()->json($this->collectResourceData());
    }

    private function collectResourceData(): array
    {
        return [
            'cpu'      => $this->getCpuLoad(),
            'memory'   => $this->getMemoryUsage(),
            'disk'     => $this->getDiskUsage(),
            'db'       => $this->getDbStats(),
            'logs'     => $this->getLogStats(),
            'queue'    => $this->getQueueStats(),
            'sessions' => $this->getSessionStats(),
            'php'      => $this->getPhpStats(),
        ];
    }

    // ── CPU ──
    private function cpuCores(): int
    {
        if (is_readable('/proc/cpuinfo')) {
            return max(1, substr_count(file_get_contents('/proc/cpuinfo'), 'processor'));
        }
        return 1;
    }

    private function getCpuLoad(): array
    {
        $load  = function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
        $cores = $this->cpuCores();

        return [
            'load1'   => round($load[0] ?? 0, 2),
            'load5'   => round($load[1] ?? 0, 2),
            'load15'  => round($load[2] ?? 0, 2),
            'cores'   => $cores,
            'percent' => $cores > 0 ? round((($load[0] ?? 0) / $cores) * 100, 1) : 0,
        ];
    }

    // ── RAM ──
    private function getMemoryUsage(): array
    {
        $meminfo = [];

        if (is_readable('/proc/meminfo')) {
            foreach (file('/proc/meminfo') as $line) {
                if (preg_match('/^(\w+):\s+(\d+)\s*kB/', $line, $m)) {
                    $meminfo[$m[1]] = (int) $m[2];
                }
            }
        }

        $totalKb     = $meminfo['MemTotal'] ?? 0;
        $availableKb = $meminfo['MemAvailable'] ?? ($meminfo['MemFree'] ?? 0);
        $usedKb      = max(0, $totalKb - $availableKb);

        return [
            'total_mb' => round($totalKb / 1024, 1),
            'used_mb'  => round($usedKb / 1024, 1),
            'free_mb'  => round($availableKb / 1024, 1),
            'percent'  => $totalKb > 0 ? round(($usedKb / $totalKb) * 100, 1) : 0,
        ];
    }

    // ── Disk ──
    private function getDiskUsage(): array
    {
        $path  = base_path();
        $total = @disk_total_space($path) ?: 0;
        $free  = @disk_free_space($path) ?: 0;
        $used  = max(0, $total - $free);

        return [
            'total_gb' => round($total / 1073741824, 2),
            'used_gb'  => round($used / 1073741824, 2),
            'free_gb'  => round($free / 1073741824, 2),
            'percent'  => $total > 0 ? round(($used / $total) * 100, 1) : 0,
        ];
    }

    // ── Database ──
    private function getDbStats(): array
    {
        try {
            $connections = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $maxConn     = DB::select("SHOW VARIABLES LIKE 'max_connections'");
            $slowQueries = DB::select("SHOW GLOBAL STATUS LIKE 'Slow_queries'");

            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
            ");

            return [
                'connections'     => (int) ($connections[0]->Value ?? 0),
                'max_connections' => (int) ($maxConn[0]->Value ?? 0),
                'slow_queries'    => (int) ($slowQueries[0]->Value ?? 0),
                'size_mb'         => $size[0]->size_mb ?? 0,
            ];
        } catch (\Throwable $e) {
            return ['connections' => 0, 'max_connections' => 0, 'slow_queries' => 0, 'size_mb' => 0];
        }
    }

    // ── Laravel Log ──
    private function getLogStats(): array
    {
        $path = storage_path('logs/laravel.log');

        if (!file_exists($path)) {
            return ['size_mb' => 0, 'error_count' => 0];
        }

        return [
            'size_mb'     => round(filesize($path) / 1048576, 2),
            'error_count' => substr_count($this->tailFile($path, 1000), '.ERROR:'),
        ];
    }

    // قراءة آخر N سطر من ملف بدون shell_exec (متوافق مع الاستضافة المشتركة)
    private function tailFile(string $path, int $lines = 1000): string
    {
        $f = @fopen($path, 'r');
        if (!$f) return '';

        $buffer = 4096;
        $output = '';
        fseek($f, 0, SEEK_END);
        $pos = ftell($f);
        $lineCount = 0;

        while ($pos > 0 && $lineCount <= $lines) {
            $seek = min($buffer, $pos);
            $pos -= $seek;
            fseek($f, $pos);
            $output = fread($f, $seek) . $output;
            $lineCount = substr_count($output, "\n");
        }

        fclose($f);
        return $output;
    }

    // ── Queue ──
    private function getQueueStats(): array
    {
        return [
            'pending' => Schema::hasTable('jobs') ? DB::table('jobs')->count() : 0,
            'failed'  => Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0,
        ];
    }

    // ── Sessions ──
    private function getSessionStats(): array
    {
        $online = UserSession::whereNull('logout_at')
            ->where('last_activity', '>=', now()->subMinutes(20))
            ->count();

        return [
            'online_users' => $online,
            'session_rows' => Schema::hasTable('sessions') ? DB::table('sessions')->count() : 0,
        ];
    }

    // ── PHP ──
    private function getPhpStats(): array
    {
        return [
            'memory_usage_mb' => round(memory_get_usage(true) / 1048576, 2),
            'memory_peak_mb'  => round(memory_get_peak_usage(true) / 1048576, 2),
            'memory_limit'    => ini_get('memory_limit'),
            'php_version'     => PHP_VERSION,
        ];
    }



}