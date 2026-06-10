<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS jenis_kode_unique');
        } else {
            Schema::table('jenis', function (Blueprint $table) {
                $table->dropUnique('jenis_kode_unique');
            });
        }

        Schema::table('jenis', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->string('kode')->unique();
        });
    }
};
