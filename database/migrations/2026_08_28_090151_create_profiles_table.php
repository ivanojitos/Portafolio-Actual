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

            $table->string('full_name', 120);
            $table->string('headline', 180);
            $table->string('location', 120)->nullable();

            $table->text('introduction');
            $table->longText('about')->nullable();

            $table->string('public_email', 180)->nullable();
            $table->string('github_url', 500)->nullable();
            $table->string('linkedin_url', 500)->nullable();

            $table->string('avatar_path')->nullable();
            $table->string('resume_path')->nullable();

            $table->boolean('is_available')->default(false);
            $table->boolean('is_published')->default(false)->index();

            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
