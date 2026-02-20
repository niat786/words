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
        Schema::table('games', function (Blueprint $table) {
            $table->string('game_key')->unique()->after('id');
            $table->string('title')->after('game_key');
            $table->text('meta_description')->nullable()->after('title');
            $table->longText('ads_schema_markup')->nullable()->after('meta_description');
            $table->string('focus_keyword')->nullable()->after('ads_schema_markup');
            $table->string('canonical_url')->nullable()->after('focus_keyword');
            $table->boolean('robots_index')->default(true)->after('canonical_url');
            $table->boolean('robots_follow')->default(true)->after('robots_index');
            $table->string('og_title')->nullable()->after('robots_follow');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('twitter_title')->nullable()->after('og_description');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->boolean('is_active')->default(true)->after('twitter_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'game_key',
                'title',
                'meta_description',
                'ads_schema_markup',
                'focus_keyword',
                'canonical_url',
                'robots_index',
                'robots_follow',
                'og_title',
                'og_description',
                'twitter_title',
                'twitter_description',
                'is_active',
            ]);
        });
    }
};
