<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Cashbox;
use Illuminate\Database\Seeder;

class CashboxSeeder extends Seeder
{
    public function run(): void
    {
        // عملات النظام الأساسية
        $currencies = ['USD','TRY','EUR'];

        // اجلب كل الفروع الموجودة (تأكد BranchSeeder شغال قبله)
        $branches = Branch::orderBy('id')->get();

        foreach ($branches as $branch) {
            foreach ($currencies as $cur) {
                $code = 'CB-' . $branch->code . '-' . $cur;

                Cashbox::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => "صندوق {$branch->name} - {$cur}",
                        'branch_id' => $branch->id,
                        'currency' => $cur,
                        'status' => 'active',
                        'opening_balance' => 0,
                    ]
                );
            }
        }
    }
}
