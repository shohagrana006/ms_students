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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('user_type');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('login_id')->nullable();
            $table->string('mobile')->nullable();
            $table->longText('address')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_number')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_number')->nullable();
            $table->string('district')->nullable();
            $table->string('country')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('net_office')->nullable();
            $table->string('designation')->nullable();
            $table->string('balance')->nullable();
            $table->string('pin')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
