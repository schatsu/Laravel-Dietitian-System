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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->index();
            $table->string('slug')->unique()->nullable()->index();
            $table->string('status')->default(false)->nullable()->index();
            $table->text('description')->nullable()->index();
            $table->longText('content')->nullable();
            $table->integer('order')->nullable()->index();
            $table->string('image')->nullable()->index();
            $table->text('seo_title')->nullable()->index();
            $table->text('seo_description')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
