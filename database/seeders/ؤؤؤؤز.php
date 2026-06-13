<?php

namespace Database\Seeders;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CashboxTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $cashboxes = [
            'MAIN-USD' => Cashbox::firstOrCreate(
                ['code' => 'MAIN-USD'],
                [
                    'name' => 'الصندوق الرئيسي دولار',
                    'branch_id' => 1,
                    'currency' => 'USD',
                    'status' => 'active',
                    'opening_balance' => 0,
                ]
            ),

            'BANK-USD' => Cashbox::firstOrCreate(
                ['code' => 'BANK-USD'],
                [
                    'name' => 'حساب البنك دولار',
                    'branch_id' => 1,
                    'currency' => 'USD',
                    'status' => 'active',
                    'opening_balance' => 0,
                ]
            ),

            'MAIN-TRY' => Cashbox::firstOrCreate(
                ['code' => 'MAIN-TRY'],
                [
                    'name' => 'الصندوق الرئيسي ليرة تركية',
                    'branch_id' => 1,
                    'currency' => 'TRY',
                    'status' => 'active',
                    'opening_balance' => 0,
                ]
            ),
        ];

        $transactions = [
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-01',
                'type' => 'in',
                'amount' => 4450,
                'currency' => 'USD',
                'category' => 'بخصوص الدبلومة',
                'sub_category' => 'قسط الدبلومة',
                'reference' => '657',
                'notes' => null,
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-03',
                'type' => 'out',
                'amount' => 300,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'مواصلات',
                'reference' => '567',
                'notes' => 'مصروف تقديم نقل',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-05',
                'type' => 'transfer',
                'amount' => 200,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'بنك',
                'reference' => 'TR-001',
                'notes' => 'من صندوق الرئيسي إلى البنك - نقل',
                'to_cashbox_code' => 'BANK-USD',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-06',
                'type' => 'exchange',
                'amount' => 100,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'تصريف عملة',
                'foreign_currency' => 'TRY',
                'foreign_amount' => 3200,
                'reference' => 'EX-001',
                'notes' => 'تصريف دولار إلى ليرة - دخل',
                'exchange_to_cashbox_code' => 'MAIN-TRY',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-07',
                'type' => 'in',
                'amount' => 8550,
                'currency' => 'USD',
                'category' => 'بخصوص الدبلومة',
                'sub_category' => 'قسط دبلومة',
                'reference' => '658',
                'notes' => 'دفعات',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-08',
                'type' => 'out',
                'amount' => 530,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'مكتبية',
                'reference' => '568',
                'notes' => 'مصروف عام',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-09',
                'type' => 'transfer',
                'amount' => 280,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'بنك',
                'reference' => 'TR-001',
                'notes' => 'من صندوق دولار إلى البنك - نقل',
                'to_cashbox_code' => 'BANK-USD',
            ],
            [
                'cashbox_code' => 'MAIN-USD',
                'trx_date' => '2026-05-20',
                'type' => 'exchange',
                'amount' => 200,
                'currency' => 'USD',
                'category' => 'مصروف',
                'sub_category' => 'تصريف عملة',
                'foreign_currency' => 'TRY',
                'foreign_amount' => 6400,
                'reference' => 'EX-001',
                'notes' => 'تصريف دولار إلى ليرة - دخل',
                'exchange_to_cashbox_code' => 'MAIN-TRY',
            ],
        ];

        foreach ($transactions as $trx) {
            CashboxTransaction::create([
                'cashbox_id' => $cashboxes[$trx['cashbox_code']]->id,
                'trx_date' => $trx['trx_date'],
                'type' => $trx['type'],
                'amount' => $trx['amount'],
                'currency' => $trx['currency'],

                'category' => $trx['category'],
                'sub_category' => $trx['sub_category'],

                'foreign_currency' => $trx['foreign_currency'] ?? null,
                'foreign_amount' => $trx['foreign_amount'] ?? null,

                'reference' => $trx['reference'] ?? null,
                'notes' => $trx['notes'] ?? null,

                'status' => 'posted',
                'posted_at' => Carbon::parse($trx['trx_date'])->setTime(12, 0),

                'to_cashbox_id' => isset($trx['to_cashbox_code'])
                    ? $cashboxes[$trx['to_cashbox_code']]->id
                    : null,

                'exchange_to_cashbox_id' => isset($trx['exchange_to_cashbox_code'])
                    ? $cashboxes[$trx['exchange_to_cashbox_code']]->id
                    : null,
            ]);
        }
    }
}