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
        if (! Schema::hasColumn('settings', 'facebook_url')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('facebook_url', 2048)->nullable()->after('global_header_code');
            });
        }

        if (! Schema::hasColumn('settings', 'instagram_url')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('instagram_url', 2048)->nullable()->after('facebook_url');
            });
        }

        if (! Schema::hasColumn('settings', 'youtube_url')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('youtube_url', 2048)->nullable()->after('instagram_url');
            });
        }

        if (! Schema::hasColumn('settings', 'x_url')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('x_url', 2048)->nullable()->after('youtube_url');
            });
        }

        if (! Schema::hasColumn('settings', 'pinterest_url')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('pinterest_url', 2048)->nullable()->after('x_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columnsToDrop = array_values(array_filter([
            Schema::hasColumn('settings', 'facebook_url') ? 'facebook_url' : null,
            Schema::hasColumn('settings', 'instagram_url') ? 'instagram_url' : null,
            Schema::hasColumn('settings', 'youtube_url') ? 'youtube_url' : null,
            Schema::hasColumn('settings', 'x_url') ? 'x_url' : null,
            Schema::hasColumn('settings', 'pinterest_url') ? 'pinterest_url' : null,
        ]));

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) use ($columnsToDrop): void {
            $table->dropColumn($columnsToDrop);
        });
    }
};
