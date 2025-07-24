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
        // Tabel untuk Paket Umroh
        Schema::create('pakets', function (Blueprint $table) {
            $table->id(); // Sama dengan @id @default(autoincrement())
            $table->string('namaPaket');
            $table->text('fotoUrl');
            $table->text('deskripsi');
            $table->integer('harga');
            $table->integer('durasi');
            $table->dateTime('tanggalKeberangkatan');
            $table->string('hotelMadinah');
            $table->string('hotelMakkah');
            $table->string('pesawat');
            $table->integer('ratingHotelMakkah')->default(5);
            $table->integer('ratingHotelMadinah')->default(5);
            $table->integer('sisaKursi');
            $table->timestamps(); // Otomatis membuat createdAt dan updatedAt
        });

        // Tabel untuk Dokumentasi
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->text('fotoUrl');
            $table->string('deskripsi')->nullable(); // nullable() sama dengan opsional (?)
            $table->timestamps();
        });

        // Tabel untuk Testimoni
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id();
            $table->string('namaJamaah');
            $table->text('deskripsiTestimoni');
            $table->text('fotoUrl');
            $table->timestamps();
        });

        // Tabel untuk Jamaah (hanya data pribadi)
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->string('namaLengkap');
            $table->string('nomorKtp')->unique();
            $table->string('nomorPaspor')->unique()->nullable();
            $table->string('tempatLahir');
            $table->dateTime('tanggalLahir');
            $table->string('jenisKelamin');
            $table->text('alamat');
            $table->string('nomorTelepon');
            $table->string('email')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('scanKtpUrl')->nullable();
            $table->text('scanPasporUrl')->nullable();
            $table->timestamps();
        });

        // Tabel untuk Pemesanan (menghubungkan jamaah dan paket)
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggalPemesanan')->useCurrent();
            $table->string('statusPembayaran');
            $table->text('catatan')->nullable();
            
            // Foreign key untuk jamaah
            $table->foreignId('jamaahId')->constrained('jamaahs')->onDelete('cascade');
            
            // Foreign key untuk paket
            $table->foreignId('paketId')->constrained('pakets')->onDelete('cascade');

            // Mencegah duplikat
            $table->unique(['jamaahId', 'paketId']);
        });

        // Tabel untuk Tabungan
        Schema::create('tabungans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggalSetoran')->useCurrent();
            $table->integer('jumlahSetoran');
            $table->string('keterangan')->nullable();

            // Foreign key untuk jamaah
            $table->foreignId('jamaahId')->constrained('jamaahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabungans');
        Schema::dropIfExists('pemesanans');
        Schema::dropIfExists('jamaahs');
        Schema::dropIfExists('testimonis');
        Schema::dropIfExists('dokumentasis');
        Schema::dropIfExists('pakets');
    }
};