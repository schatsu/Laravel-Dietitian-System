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
        Schema::create('client_lifestyle_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Client::class)->constrained()->cascadeOnDelete();
            $table->string('activity_level')->nullable()->index();
            $table->string('sleep_hours')->nullable()->index();
            $table->string('water_intake')->nullable()->index();
            $table->string('smoking_status')->nullable()->index();
            $table->string('alcohol_consumption')->nullable()->index();
            $table->text('extra_notes')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_lifestyle_profiles');
    }
};
