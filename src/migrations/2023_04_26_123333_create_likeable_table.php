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
        Schema::create('likes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('likeable_id', 36);
            $table->string('likeable_type', 255);
            $table->string('user_id', 36)->index();
            $table->timestamps();
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likeable_likes_unique');
        });

        Schema::create('like_counters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('likeable_id', 36);
            $table->string('likeable_type', 255);
            $table->unsignedInteger('count')->default(0);
            $table->unique(['likeable_id', 'likeable_type'], 'likeable_counts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('like_counters');
    }
};
