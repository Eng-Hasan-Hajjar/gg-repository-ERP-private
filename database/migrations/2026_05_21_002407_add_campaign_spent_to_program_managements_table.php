<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_managements', function (Blueprint $table) {
            // ✅ مصروف الحملة الفعلي
            $table->decimal('campaign_spent', 10, 2)->nullable()->after('campaign_budget');

            // ✅ روابط حقول الميديا
            $table->string('direct_ads_link')->nullable()->after('direct_ads');
            $table->string('opening_invitation_link')->nullable()->after('opening_invitation');
            $table->string('opening_snippets_link')->nullable()->after('opening_snippets');
            $table->string('carousel_link')->nullable()->after('carousel');
            $table->string('designs_link')->nullable()->after('designs');
            $table->string('stories_link')->nullable()->after('stories');
            $table->string('media_form_sent_link')->nullable()->after('media_form_sent');
            $table->string('content_ready_link')->nullable()->after('content_ready');
            // ✅ حقلان جديدان للستوريات
            $table->integer('stories_done')->nullable()->after('stories_link');    // المُنجز
            $table->integer('stories_total')->nullable()->after('stories_done');   // الإجمالي

        });
    }

    public function down(): void
    {
        Schema::table('program_managements', function (Blueprint $table) {
            $table->dropColumn([
                'campaign_spent',
                'direct_ads_link',
                'opening_invitation_link',
                'opening_snippets_link',
                'carousel_link',
                'designs_link',
                'stories_link',
                'media_form_sent_link',
                'content_ready_link',
                'stories_done',
                'stories_total', // ✅
            ]);
        });
    }
};
