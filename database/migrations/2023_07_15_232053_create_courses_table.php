<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->string('rating')->nullable();
            $table->string('best_seller')->nullable();
            $table->string('top_course')->nullable();
            $table->string('student_view')->nullable();
            $table->string('enroll')->nullable();
            $table->string('comment')->nullable();
            $table->string('lecture')->nullable();
            $table->string('quizzes')->nullable();
            $table->string('skill_level')->nullable();
            $table->string('assessment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
