<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status','active')->get();

        if ($employees->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد موظفين، تخطّي LeaveRequestSeeder');
            return;
        }

        $base = now()->startOfMonth();

        $templates = [
            ['type'=>'leave', 'status'=>'pending',  'offset'=>5,  'days'=>2, 'reason'=>'ظرف عائلي'],
            ['type'=>'leave', 'status'=>'approved', 'offset'=>12, 'days'=>1, 'reason'=>'موعد طبي'],
            ['type'=>'permission', 'status'=>'rejected','offset'=>18,'days'=>0,'reason'=>'إذن خروج مبكر'],
        ];

        foreach ($templates as $k => $t) {
            $emp = $employees->get($k % $employees->count());
            $start = $base->copy()->addDays($t['offset'])->toDateString();
            $end   = $t['days'] ? $base->copy()->addDays($t['offset'] + $t['days'])->toDateString() : null;

            // مفتاح عدم تضارب: employee + start_date + type
            LeaveRequest::updateOrCreate(
                ['employee_id'=>$emp->id, 'start_date'=>$start, 'type'=>$t['type']],
                [
                    'end_date' => $end,
                    'reason'   => $t['reason'].' (seed)',
                    'status'   => $t['status'],
                    'admin_note' => $t['status']==='rejected' ? 'السبب: ضغط عمل (seed)' : null,
                    'approved_by' => null,
                    'approved_at' => $t['status']!=='pending' ? now() : null,
                ]
            );
        }
    }
}
