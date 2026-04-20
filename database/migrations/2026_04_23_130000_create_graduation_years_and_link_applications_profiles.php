<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduation_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('graduation_year');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('graduation_year_id')->nullable()->after('specialization_id')->constrained('graduation_years')->restrictOnDelete();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('graduation_year');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignId('graduation_year_id')->nullable()->after('specialization_id')->constrained('graduation_years')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['graduation_year_id']);
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('graduation_year_id');
            $table->unsignedSmallInteger('graduation_year')->after('specialization_id');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['graduation_year_id']);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('graduation_year_id');
            $table->unsignedSmallInteger('graduation_year')->after('specialization_id');
        });

        Schema::dropIfExists('graduation_years');
    }
};
