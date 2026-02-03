<?php
// database/seeders/LeadSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Branch;
use App\Models\Diploma;
use Illuminate\Support\Str;

class LeadSeeder extends Seeder
{
  public function run(): void
  {
    $branches = Branch::all();
    $diplomas = Diploma::all();

    if ($branches->isEmpty() || $diplomas->isEmpty()) {
      $this->command->warn('تأكد من وجود branches و diplomas قبل LeadSeeder.');
      return;
    }

    $sources = ['ad','referral','social','website','expo','other'];
    $stages  = ['new','follow_up','interested','registered','rejected','postponed'];

    for ($i=1; $i<=60; $i++) {
      $branch = $branches->random();

      $lead = Lead::create([
        'full_name' => 'عميل محتمل '.$i.' '.Str::random(4),
        'phone' => '+9639'.rand(10000000,99999999),
        'whatsapp' => '+9639'.rand(10000000,99999999),
        'first_contact_date' => now()->subDays(rand(0,60))->toDateString(),
        'residence' => ['حلب','ادلب','اسطنبول','مرسين','بورصة','عنتاب','كليس','اونلاين'][rand(0,7)],
        'age' => rand(16,45),
        'organization' => ['جامعة','شركة','معهد','مركز'][rand(0,3)],
        'source' => $sources[array_rand($sources)],
        'need' => 'يريد التسجيل في دبلومة/برنامج مناسب.',
        'stage' => $stages[array_rand($stages)],
        'registration_status' => 'pending',
        'notes' => 'ملاحظات افتراضية للعميل '.$i,
        'branch_id' => $branch->id,
        'created_by' => null,
      ]);

      // attach 1-3 diplomas
      $pick = $diplomas->random(rand(1, min(3,$diplomas->count())));
      $sync = [];
      foreach ($pick as $k=>$d) $sync[$d->id] = ['is_primary'=>$k===0];
      $lead->diplomas()->sync($sync);

      // followups 0-2
      $countFollow = rand(0,2);
      for($f=1;$f<=$countFollow;$f++){
        $lead->followups()->create([
          'followup_date' => now()->subDays(rand(0,30))->toDateString(),
          'result' => ['تم التواصل','لا يرد','مهتم','طلب تأجيل'][rand(0,3)],
          'notes' => 'متابعة رقم '.$f.' للعميل '.$i,
          'created_by' => null,
        ]);
      }
    }
  }
}
