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
        Schema::create('campaign_postbacks', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->text('data');
            $table->tinyInteger('status')->default(0);
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_postbacks');
    }
};
