<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\User;
use App\Models\LeadFollowup;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $diplomas = Diploma::all();
        $users    = User::all();

        if ($branches->isEmpty() || $diplomas->isEmpty() || $users->isEmpty()) {
            $this->command->warn('⚠️ تأكد من وجود فروع ودبلومات ومستخدمين قبل تشغيل LeadSeeder');
            return;
        }

        $sources = ['ad','referral','social','website','expo','other'];
        $stages  = ['new','follow_up','interested','postponed','rejected'];
        $names   = [
            'محمد أحمد','أحمد خالد','علي محمود','سارة حسن','نور علي',
            'ريم محمد','خالد يوسف','عبد الرحمن عمر','لينا سامر','فاطمة حسين',
            'مصطفى إبراهيم','ياسر العبد','هبة الله','زين العابدين','أنس طارق',
            'مريم خليل','وسيم جابر','رنا محمود','عبد الله علي','نورهان فؤاد'
        ];

        // ✅ إنشاء 60 Lead افتراضي
        for ($i = 1; $i <= 60; $i++) {

            $fullName = Arr::random($names) . ' ' . rand(1, 999);
            $branch   = $branches->random();
            $creator  = $users->random();

            $lead = Lead::create([
                'full_name' => $fullName,
                'phone' => '+90' . rand(500000000, 599999999),
                'whatsapp' => '+90' . rand(500000000, 599999999),

                'first_contact_date' => Carbon::now()->subDays(rand(1, 90)),
                'residence' => Arr::random(['اسطنبول','غازي عنتاب','مرسين','بورصة','ألمانيا','أونلاين']),
                'age' => rand(18, 45),
                'organization' => Arr::random(['طالب جامعي','موظف','حر','شركة خاصة',null]),

                'source' => Arr::random($sources),
                'need'   => 'الاستفسار عن الدبلومات المتاحة وآلية التسجيل',

                'stage'  => Arr::random($stages),
                'registration_status' => 'pending',

                'notes' => Arr::random([
                    'يرغب بالدراسة أونلاين',
                    'بحاجة خصم',
                    'بانتظار قرار',
                    'مهتم جدًا',
                    'طلب تفاصيل أكثر'
                ]),

                'branch_id' => $branch->id,
                'created_by' => $creator->id,
            ]);

            // 🎓 ربط 1–3 دبلومات
            $selectedDiplomas = $diplomas->random(rand(1, 3));

            $syncData = [];
            foreach ($selectedDiplomas as $index => $diploma) {
                $syncData[$diploma->id] = [
                    'is_primary' => $index === 0
                ];
            }
            $lead->diplomas()->sync($syncData);

            // 📞 إنشاء متابعات (0–3)
            $followupsCount = rand(0, 3);
            for ($f = 1; $f <= $followupsCount; $f++) {
                LeadFollowup::create([
                    'lead_id' => $lead->id,
                    'followup_date' => Carbon::now()->subDays(rand(0, 30)),
                    'result' => Arr::random([
                        'تم التواصل',
                        'لم يتم الرد',
                        'مهتم',
                        'طلب تأجيل',
                        'بانتظار قرار'
                    ]),
                    'notes' => Arr::random([
                        'سيتم التواصل لاحقًا',
                        'يرغب بعرض سعر',
                        'طلب معلومات إضافية',
                        null
                    ]),
                    'created_by' => $creator->id,
                ]);
            }
        }

        $this->command->info('✅ تم إنشاء أكثر من 60 Lead افتراضي مع دبلومات ومتابعات');
    }
}
