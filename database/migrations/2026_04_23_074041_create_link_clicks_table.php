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
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();

            $table->foreignUlid('link_id')->constrained()->onDelete('cascade');

            $table->ipAddress('ip_address')->nullable();

            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();

            $table->text('referer')->nullable();

            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            $table->timestamp('clicked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_clicks');
    }
};
