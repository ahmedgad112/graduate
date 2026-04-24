<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('governorate', 64)->nullable()->after('address');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('governorate', 64)->nullable()->after('national_id');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('governorate');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('governorate');
        });
    }
};
