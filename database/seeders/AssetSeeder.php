<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $branches   = Branch::all();
        $categories = AssetCategory::all();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تخطّي AssetSeeder');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد تصنيفات أصول، تخطّي AssetSeeder');
            return;
        }

        // أمثلة أصول افتراضية (للتجربة)
        $items = [
            // IT
            [
                'name' => 'حاسوب مكتبي Dell OptiPlex',
                'description' => 'حاسوب مكتبي لقسم الإدارة — للاستخدام اليومي.',
                'category_code' => 'IT',
                'condition' => 'good',
                'purchase_cost' => 450,
                'currency' => 'USD',
                'location' => 'غرفة الإدارة',
            ],
            [
                'name' => 'حاسوب محمول Lenovo ThinkPad',
                'description' => 'للمدرّب/الموظف — محمول للاجتماعات والدروس.',
                'category_code' => 'IT',
                'condition' => 'maintenance',
                'purchase_cost' => 620,
                'currency' => 'USD',
                'location' => 'غرفة المدرّبين',
            ],

            // NET
            [
                'name' => 'راوتر MikroTik',
                'description' => 'راوتر رئيسي للفرع — إدارة الشبكة.',
                'category_code' => 'NET',
                'condition' => 'good',
                'purchase_cost' => 180,
                'currency' => 'USD',
                'location' => 'غرفة الشبكات',
            ],
            [
                'name' => 'سويتش 24 منفذ TP-Link',
                'description' => 'سويتش لتوزيع الشبكة داخل القاعات.',
                'category_code' => 'NET',
                'condition' => 'good',
                'purchase_cost' => 95,
                'currency' => 'USD',
                'location' => 'مخزن الشبكات',
            ],

            // FURN
            [
                'name' => 'مكتب خشبي',
                'description' => 'مكتب للموظفين — مقاس متوسط.',
                'category_code' => 'FURN',
                'condition' => 'good',
                'purchase_cost' => 120,
                'currency' => 'USD',
                'location' => 'قسم الإدارة',
            ],
            [
                'name' => 'كرسي مكتبي',
                'description' => 'كرسي مريح للدوام الطويل.',
                'category_code' => 'FURN',
                'condition' => 'good',
                'purchase_cost' => 60,
                'currency' => 'USD',
                'location' => 'قسم الإدارة',
            ],

            // PROJ / LAB (إذا موجودة عندك)
            [
                'name' => 'بروجكتور Epson',
                'description' => 'بروجكتور للقاعة — عرض شرائح ودروس.',
                'category_code' => 'PROJ',
                'condition' => 'out_of_service',
                'purchase_cost' => 300,
                'currency' => 'USD',
                'location' => 'قاعة 1',
            ],
            [
                'name' => 'طابعة HP LaserJet',
                'description' => 'طابعة لطباعة تقارير ومستندات.',
                'category_code' => 'IT',
                'condition' => 'good',
                'purchase_cost' => 210,
                'currency' => 'USD',
                'location' => 'غرفة الإدارة',
            ],
        ];

        foreach ($items as $i) {

            // اختر فرع عشوائي للتجربة
            $branch = $branches->random();

            // اجلب التصنيف حسب code (وإذا غير موجود استخدم تصنيف عشوائي)
            $category = $categories->firstWhere('code', $i['category_code']) ?? $categories->random();

            // asset_tag فريد
            $assetTag = $this->generateAssetTag();

            // serial (اختياري) — نجعلها فريدة أيضًا للتجربة
            $serial = 'SN-' . Str::upper(Str::random(10));

            Asset::firstOrCreate(
                ['asset_tag' => $assetTag],
                [
                    'name' => $i['name'],
                    'description' => $i['description'] ?? null,
                    'asset_category_id' => $category?->id,
                    'branch_id' => $branch?->id,
                    'condition' => $i['condition'] ?? 'good',
                    'purchase_date' => now()->subDays(rand(30, 1200))->toDateString(),
                    'purchase_cost' => $i['purchase_cost'] ?? null,
                    'currency' => $i['currency'] ?? 'USD',
                    'serial_number' => $serial,
                    'location' => $i['location'] ?? null,
                    'photo_path' => null,
                ]
            );
        }

        $this->command->info('✅ تم Seed للأصول بنجاح (AssetSeeder)');
    }

    private function generateAssetTag(): string
    {
        do {
            $tag = 'AST-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Asset::where('asset_tag', $tag)->exists());

        return $tag;
    }
}
