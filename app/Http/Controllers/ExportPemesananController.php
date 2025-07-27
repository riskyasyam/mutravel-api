<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class ExportPemesananController extends Controller
{
    public function export()
    {
        // Ambil data dengan relasi jamaah dan paket
        $pemesanans = Pemesanan::with(['jamaah', 'paket'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'ID Pemesanan');
        $sheet->setCellValue('B1', 'Tanggal Pemesanan');
        $sheet->setCellValue('C1', 'Status Pembayaran');
        $sheet->setCellValue('D1', 'Catatan');
        $sheet->setCellValue('E1', 'Nama Paket');
        $sheet->setCellValue('F1', 'Tanggal Keberangkatan');
        $sheet->setCellValue('G1', 'ID Jamaah');
        $sheet->setCellValue('H1', 'Nama Lengkap');
        $sheet->setCellValue('I1', 'Nomor KTP');
        $sheet->setCellValue('J1', 'Nomor Paspor');
        $sheet->setCellValue('K1', 'Tempat Lahir');
        $sheet->setCellValue('L1', 'Tanggal Lahir');
        $sheet->setCellValue('M1', 'Jenis Kelamin');
        $sheet->setCellValue('N1', 'Alamat');
        $sheet->setCellValue('O1', 'Nomor Telepon');
        $sheet->setCellValue('P1', 'Email');
        $sheet->setCellValue('Q1', 'Pekerjaan');

        $row = 2;

        foreach ($pemesanans as $pemesanan) {
            $jamaah = $pemesanan->jamaah;
            $paket = $pemesanan->paket;

            $sheet->setCellValue("A{$row}", $pemesanan->id);
            $sheet->setCellValue("B{$row}", $pemesanan->tanggalPemesanan);
            $sheet->setCellValue("C{$row}", $pemesanan->statusPembayaran);
            $sheet->setCellValue("D{$row}", $pemesanan->catatan);
            $sheet->setCellValue("E{$row}", $paket?->namaPaket ?? ''); // Nama Paket
            $sheet->setCellValue("F{$row}", $paket?->tanggalKeberangkatan ?? ''); // Tanggal Keberangkatan

            if ($jamaah) {
                $sheet->setCellValue("G{$row}", $jamaah->id);
                $sheet->setCellValue("H{$row}", $jamaah->namaLengkap);
                $sheet->setCellValue("I{$row}", $jamaah->nomorKtp);
                $sheet->setCellValue("J{$row}", $jamaah->nomorPaspor);
                $sheet->setCellValue("K{$row}", $jamaah->tempatLahir);
                $sheet->setCellValue("L{$row}", $jamaah->tanggalLahir);
                $sheet->setCellValue("M{$row}", $jamaah->jenisKelamin);
                $sheet->setCellValue("N{$row}", $jamaah->alamat);
                $sheet->setCellValue("O{$row}", $jamaah->nomorTelepon);
                $sheet->setCellValue("P{$row}", $jamaah->email);
                $sheet->setCellValue("Q{$row}", $jamaah->pekerjaan);
            }

            $row++;
        }

        // Buat dan kirim file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pemesanan.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}