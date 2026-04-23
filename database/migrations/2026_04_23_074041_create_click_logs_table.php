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
        Schema::create('click_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('link_id')->constrained()->cascadeOnDelete();

            $table->string('ip_address', 45)->nullable();

            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();

            $table->text('referer')->nullable();

            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            $table->timestamp('clicked_at')->useCurrent();

            $table->timestamps();

            $table->index('link_id');
            $table->index('clicked_at');
            $table->index('country');
            $table->index('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_logs');
    }
};
