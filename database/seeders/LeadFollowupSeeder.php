<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

class LeadFollowupSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        Lead::all()->each(function ($lead) use ($users) {

            $count = rand(1, 4);

            for ($i = 0; $i < $count; $i++) {
                LeadFollowup::create([
                    'lead_id' => $lead->id,
                    'followup_date' => Carbon::now()->subDays(rand(1, 40)),
                    'result' => Arr::random([
                        'تم التواصل',
                        'لم يتم الرد',
                        'مهتم',
                        'بانتظار دفع',
                        'تأجيل'
                    ]),
                    'notes' => Arr::random([
                        'طلب خصم',
                        'سجل متابعة',
                        null
                    ]),
                    'created_by' => $users->random()->id,
                ]);
            }
        });
    }
}
