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
        Schema::table('obat', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('nama')->constrained('kategori')->onDelete('set null');
            $table->foreignId('jenis_id')->nullable()->after('kategori_id')->constrained('jenis')->onDelete('set null');
            $table->integer('stok_min')->default(0)->after('stok');
            $table->foreignId('satuan_id')->nullable()->after('stok_min')->constrained('satuan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropForeign(['jenis_id']);
            $table->dropForeign(['satuan_id']);
            $table->dropColumn(['kategori_id', 'jenis_id', 'stok_min', 'satuan_id']);
        });
    }
};
