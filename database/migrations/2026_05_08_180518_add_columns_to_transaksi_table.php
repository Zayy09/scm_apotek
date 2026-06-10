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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('obat_id')->after('id');
            $table->date('tanggal')->after('jumlah');
            $table->string('keterangan')->nullable()->after('tanggal');

            // Tambahkan foreign key constraint jika perlu
            // $table->foreign('obat_id')->references('id')->on('obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['obat_id', 'tanggal', 'keterangan']);
        });
    }
};
