<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('residence_region', 255)->nullable()->after('governorate');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('residence_region', 255)->nullable()->after('governorate');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('residence_region');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('residence_region');
        });
    }
};
