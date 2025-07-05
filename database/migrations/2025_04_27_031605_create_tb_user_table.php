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
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();  // tambahkan kolom username
            $table->string('password');            // tambahkan kolom password (hash)
            $table->string('password_plain');      // tambahkan kolom password_plain (opsional)
            $table->enum('level', ['superadmin', 'admin']); // level user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user');
    }
};
