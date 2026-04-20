<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('photo_path');
            $table->text('certificates_text')->nullable()->after('skills');
            $table->string('employment_status', 32)->nullable()->after('certificates_text');
            $table->boolean('exempt_from_military')->default(false)->after('employment_status');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('photo_path');
            $table->text('certificates_text')->nullable()->after('skills');
            $table->string('employment_status', 32)->nullable()->after('certificates_text');
            $table->boolean('exempt_from_military')->default(false)->after('employment_status');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['skills', 'certificates_text', 'employment_status', 'exempt_from_military']);
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['skills', 'certificates_text', 'employment_status', 'exempt_from_military']);
        });
    }
};
