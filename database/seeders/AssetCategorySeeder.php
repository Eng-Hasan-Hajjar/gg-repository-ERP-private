<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'أجهزة وتقنية معلومات', 'code' => 'IT'],
            ['name' => 'شبكات واتصالات',       'code' => 'NET'],
            ['name' => 'أثاث وتجهيزات',        'code' => 'FURN'],
            ['name' => 'أجهزة تعليمية',        'code' => 'EDU'],
            ['name' => 'طابعات وملحقات',       'code' => 'PRN'],
            ['name' => 'كاميرات ومراقبة',      'code' => 'CCTV'],
            ['name' => 'معدات كهرباء',         'code' => 'ELEC'],
            ['name' => 'قرطاسية ومستهلكات',    'code' => 'STAT'],
            ['name' => 'مخزن عام',             'code' => 'WH'],
        ];

        foreach ($items as $i) {
            AssetCategory::firstOrCreate(['code' => $i['code']], $i);
        }
    }
}
