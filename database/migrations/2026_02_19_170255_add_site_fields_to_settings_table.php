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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('site_name')->nullable()->after('enabled_games');
            $table->string('site_tagline')->nullable()->after('site_name');
            $table->string('logo_path')->nullable()->after('site_tagline');
            $table->string('favicon_path')->nullable()->after('logo_path');
            $table->string('apple_touch_icon_path')->nullable()->after('favicon_path');
            $table->longText('global_header_code')->nullable()->after('apple_touch_icon_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name',
                'site_tagline',
                'logo_path',
                'favicon_path',
                'apple_touch_icon_path',
                'global_header_code',
            ]);
        });
    }
};
