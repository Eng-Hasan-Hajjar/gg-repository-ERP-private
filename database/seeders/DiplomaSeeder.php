<?php

namespace Database\Seeders;

use App\Models\Diploma;
use Illuminate\Database\Seeder;

class DiplomaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'هندسة البرمجيات',              'code' => 'SE',     'field' => 'Software',      'is_active' => true],
            ['name' => 'تطوير الويب (Full-Stack)',     'code' => 'WEBFS',  'field' => 'Web',           'is_active' => true],
            ['name' => 'Laravel Backend',              'code' => 'LAR',    'field' => 'Backend',       'is_active' => true],
            ['name' => 'Flutter Mobile',               'code' => 'FLU',    'field' => 'Mobile',        'is_active' => true],
            ['name' => 'Python برمجة',                 'code' => 'PY',     'field' => 'Programming',   'is_active' => true],
            ['name' => 'تحليل البيانات',               'code' => 'DATA',   'field' => 'Data',          'is_active' => true],
            ['name' => 'الذكاء الاصطناعي',             'code' => 'AI',     'field' => 'AI',            'is_active' => true],
            ['name' => 'الشبكات + CCNA',               'code' => 'CCNA',   'field' => 'Networking',    'is_active' => true],
            ['name' => 'أمن المعلومات',                'code' => 'SEC',    'field' => 'Cybersecurity', 'is_active' => true],
            ['name' => 'ICDL مهارات الحاسوب',           'code' => 'ICDL',   'field' => 'Basics',        'is_active' => true],
        ];

        foreach ($items as $i) {
            Diploma::firstOrCreate(['code' => $i['code']], $i);
        }
    }
}
