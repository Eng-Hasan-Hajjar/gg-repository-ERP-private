<?php
namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature   = 'attendance:mark-absent';
    protected $description = 'تحويل سجلات scheduled إلى absent بعد الساعة المحددة';

    public function handle(): void
    {
        $hourSetting = SystemSetting::get('absent_after_hour', '18:00');
        $cutoff      = Carbon::today()->setTimeFromTimeString($hourSetting);

        // إذا لم يحن الوقت بعد — لا تفعل شيئاً
        if (now()->lessThan($cutoff)) {
            $this->info("لم يحن وقت التحويل — الحد: {$hourSetting}");
            return;
        }

        $updated = AttendanceRecord::whereDate('work_date', today())
            ->where('status', 'scheduled')
            ->whereNull('check_in_at')
            ->update(['status' => 'absent']);

        $this->info("تم تحويل {$updated} موظف إلى غائب.");
    }
}