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
        Schema::create('links', function (Blueprint $table) {
            $table->ulid("id")->primary();

            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();

            $table->string('title')->nullable();

            $table->longText('original_url');

            $table->string('short_code')->unique();
            $table->string('custom_alias')->nullable()->unique();

            $table->string('password')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();

            $table->timestamps();

            $table->index('short_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
