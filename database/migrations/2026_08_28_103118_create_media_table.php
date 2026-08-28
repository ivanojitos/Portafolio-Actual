<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->morphs('mediable');

            $table->string('disk', 40)->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');

            $table->string('alt_text', 180)->nullable();
            $table->json('metadata')->nullable();

            $table->boolean('is_primary')->default(false)->index();
            $table->unsignedSmallInteger('position')->default(0);

            $table->timestamps();

            $table->index([
                'mediable_type',
                'mediable_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
