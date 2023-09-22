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
        Schema::create('student_refers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_login_id');
            $table->foreignId('ref_login_id');
            $table->foreignId('placement_login_id');
            $table->tinyInteger('position')->comment('1 for A, 2 for B');
            $table->foreignId('net_office');
            $table->tinyInteger('status')->default(0)->comment('0 for pending, 1 for approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('student_refers');
    }
};
