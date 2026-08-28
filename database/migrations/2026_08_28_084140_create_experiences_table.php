<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();

            $table->string('company', 120);
            $table->string('job_title', 120);
            $table->string('employment_type', 40)->nullable();
            $table->string('location', 120)->nullable();
            $table->string('company_url', 500)->nullable();

            $table->text('summary');
            $table->json('achievements')->nullable();

            $table->date('started_at');
            $table->date('ended_at')->nullable();

            $table->boolean('is_current')->default(false);
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedSmallInteger('position')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'is_published',
                'position',
                'started_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
