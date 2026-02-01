<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $branches  = Branch::all();
        $employees = Employee::where('status','active')->get();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تخطّي TaskSeeder');
            return;
        }

        // ✅ آمن: أول مستخدم موجود أو null
       // $creatorId = User::query()->value('id'); // يرجع أول id أو null
$creatorId = User::where('email','admin@namaa.test')->value('id');

        $items = [
            ['title'=>'تجهيز تقرير الدوام الأسبوعي', 'priority'=>'high',   'status'=>'in_progress', 'due'=>now()->addDays(2)],
            ['title'=>'مراجعة طلبات الإجازات',       'priority'=>'urgent', 'status'=>'todo',        'due'=>now()->addDay()],
            ['title'=>'تحديث بيانات موظف جديد',      'priority'=>'medium', 'status'=>'done',        'due'=>now()->subDay()],
            ['title'=>'تدقيق الحضور المتأخر',        'priority'=>'high',   'status'=>'todo',        'due'=>now()->addDays(3)],
            ['title'=>'تحضير تقرير أداء الشهر',      'priority'=>'medium', 'status'=>'blocked',     'due'=>now()->addDays(5)],
        ];

        foreach ($items as $i) {
            $branch   = $branches->random();
            $assignee = $employees->isNotEmpty() ? $employees->random() : null;

            Task::updateOrCreate(
                [
                    'title'    => $i['title'],
                    'due_date' => $i['due']->format('Y-m-d'),
                ],
                [
                    'branch_id'    => $branch->id,
                    'assigned_to'  => $assignee?->id,
                    'priority'     => $i['priority'],
                    'status'       => $i['status'],
                    'description'  => 'مهمة افتراضية للتجربة (seed)',
                    'created_by'   => $creatorId, // ✅ null إذا ما في users
                ]
            );
        }

        $this->command->info('✅ تم Seed للمهام بنجاح (TaskSeeder).');
    }
}
