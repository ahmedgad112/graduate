<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('national_id')->unique();
            $table->string('university_name');
            $table->string('department');
            $table->string('specialization');
            $table->unsignedSmallInteger('graduation_year');
            $table->string('grade', 32);
            $table->decimal('gpa', 4, 2)->nullable();
            $table->string('cv_path')->nullable();
            $table->string('cert_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
