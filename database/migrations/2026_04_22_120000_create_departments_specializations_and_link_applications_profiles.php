<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['department', 'specialization']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('university_id')->constrained()->restrictOnDelete();
            $table->foreignId('specialization_id')->nullable()->after('department_id')->constrained()->restrictOnDelete();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['department', 'specialization']);
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('university_name')->constrained()->restrictOnDelete();
            $table->foreignId('specialization_id')->nullable()->after('department_id')->constrained()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['specialization_id']);
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['department_id', 'specialization_id']);
            $table->string('department')->after('university_name');
            $table->string('specialization')->after('department');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['specialization_id']);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['department_id', 'specialization_id']);
            $table->string('department')->after('university_id');
            $table->string('specialization')->after('department');
        });

        Schema::dropIfExists('specializations');
        Schema::dropIfExists('departments');
    }
};
