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
        $columnsToDrop = array_values(array_filter([
            Schema::hasColumn('settings', 'default_meta_title') ? 'default_meta_title' : null,
            Schema::hasColumn('settings', 'default_meta_description') ? 'default_meta_description' : null,
            Schema::hasColumn('settings', 'default_meta_keywords') ? 'default_meta_keywords' : null,
        ]));

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) use ($columnsToDrop): void {
            $table->dropColumn($columnsToDrop);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('settings', 'default_meta_title')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('default_meta_title')->nullable()->after('apple_touch_icon_path');
            });
        }

        if (! Schema::hasColumn('settings', 'default_meta_description')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->text('default_meta_description')->nullable()->after('default_meta_title');
            });
        }

        if (! Schema::hasColumn('settings', 'default_meta_keywords')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('default_meta_keywords')->nullable()->after('default_meta_description');
            });
        }
    }
};
