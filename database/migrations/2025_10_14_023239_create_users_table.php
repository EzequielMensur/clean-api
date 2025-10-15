<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username', 60)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
