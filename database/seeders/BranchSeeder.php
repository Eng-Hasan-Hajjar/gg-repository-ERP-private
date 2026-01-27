<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name'=>'ألمانيا', 'code'=>'DE'],
            ['name'=>'اسطنبول', 'code'=>'IST'],
            ['name'=>'مرسين', 'code'=>'MRS'],
            ['name'=>'بورصة', 'code'=>'BRS'],
            ['name'=>'كليس', 'code'=>'KLS'],
            ['name'=>'عنتاب', 'code'=>'ANT'],
            ['name'=>'أونلاين', 'code'=>'ONL'],
        ];

        foreach ($items as $i) {
            Branch::firstOrCreate(['code' => $i['code']], $i);
        }
    }
}
