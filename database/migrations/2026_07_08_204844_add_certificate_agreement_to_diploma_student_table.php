// php artisan make:migration add_certificate_agreement_to_diploma_student_table
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('diploma_student', function (Blueprint $table) {
            $table->string('certificate_agreement')->nullable()->after('certificate_delivered');
            $table->string('language_level')->nullable()->after('certificate_delivered');
     
            });
    }

    public function down(): void
    {
        Schema::table('diploma_student', function (Blueprint $table) {
            $table->dropColumn('certificate_agreement');
             $table->dropColumn('language_level');
        });
    }
};