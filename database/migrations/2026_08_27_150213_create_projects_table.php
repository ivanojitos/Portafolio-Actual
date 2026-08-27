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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('title', 120);
            $table->string('slug', 140)->unique();
            $table->string('summary', 300);

            $table->text('challenge');
            $table->text('solution');
            $table->text('results')->nullable();

            $table->string('repository_url', 500)->nullable();
            $table->string('demo_url', 500)->nullable();
            $table->string('cover_image')->nullable();

            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedSmallInteger('position')->default(0);

            $table->timestamp('published_at')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'is_published',
                'is_featured',
                'position',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
