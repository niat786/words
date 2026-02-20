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
        if (! Schema::hasColumn('settings', 'default_game')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('default_game')->default('wordle');
            });
        }

        if (! Schema::hasColumn('settings', 'enabled_games')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->json('enabled_games')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('settings', 'enabled_games')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('enabled_games');
            });
        }

        if (Schema::hasColumn('settings', 'default_game')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('default_game');
            });
        }
    }
};
