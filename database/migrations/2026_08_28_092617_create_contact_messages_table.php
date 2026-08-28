<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('email', 180);
            $table->string('company', 120)->nullable();
            $table->string('subject', 160);
            $table->text('message');

            $table->string('status', 20)->default('pending')->index();

            $table->char('ip_hash', 64)->nullable()->index();
            $table->string('user_agent', 500)->nullable();

            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->index([
                'status',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
