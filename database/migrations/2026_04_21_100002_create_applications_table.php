<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('national_id');
            $table->text('address');
            $table->foreignId('university_id')->constrained()->restrictOnDelete();
            $table->string('department');
            $table->string('specialization');
            $table->unsignedSmallInteger('graduation_year');
            $table->string('grade', 32);
            $table->decimal('gpa', 4, 2)->nullable();
            $table->string('cv_path')->nullable();
            $table->string('cert_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status', 32)->default('pending');
            $table->timestamps();

            $table->unique('email');
            $table->unique('phone');
            $table->unique('national_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
