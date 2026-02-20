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
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('seo_title', 70)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('focus_keyword', 120)->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('schema_type')->default('BlogPosting');
            $table->longText('schema_markup_json')->nullable();
            $table->unsignedTinyInteger('seo_score')->nullable();
            $table->string('seo_grade', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'meta_description',
                'focus_keyword',
                'canonical_url',
                'robots_index',
                'robots_follow',
                'og_title',
                'og_description',
                'twitter_title',
                'twitter_description',
                'schema_type',
                'schema_markup_json',
                'seo_score',
                'seo_grade',
            ]);
        });
    }
};
