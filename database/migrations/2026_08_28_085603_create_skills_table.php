<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            $table->string('name', 60);
            $table->string('slug', 70)->unique();
            $table->string('category', 30);
            $table->string('level', 20);

            $table->string('summary', 250)->nullable();
            $table->unsignedTinyInteger('years_experience')->nullable();

            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedSmallInteger('position')->default(0);

            $table->timestamps();

            $table->index([
                'is_published',
                'category',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
