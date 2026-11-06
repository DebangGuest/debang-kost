<?php
require_once "koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    // 1. Ambil pendaftar baru (terbaru di atas)
    $stmtPendaftar = $pdo->query("SELECT * FROM pendaftar ORDER BY id DESC");
    $pendaftar = $stmtPendaftar->fetchAll();

    // 2. Ambil 9 unit kamar Debang Guest
    $stmtKamar = $pdo->query("SELECT * FROM kamar ORDER BY id ASC");
    $kamar = $stmtKamar->fetchAll();

    // 3. Ambil data kas keuangan
    $stmtKeuangan = $pdo->query("SELECT * FROM keuangan ORDER BY tanggal DESC, id DESC");
    $keuangan = $stmtKeuangan->fetchAll();

    // Hitung ringkasan kas & kamar kosong
    $total_in = 0;
    $total_out = 0;
    foreach ($keuangan as $k) {
        if ($k['tipe'] === 'in') {
            $total_in += (float)$k['nominal'];
        } else {
            $total_out += (float)$k['nominal'];
        }
    }

    $kamar_kosong = 0;
    foreach ($kamar as $km) {
        if ($km['status'] === 'tersedia') {
            $kamar_kosong++;
        }
    }

    echo json_encode([
        "status"       => true,
        "kamar_kosong" => $kamar_kosong,
        "ringkasan"    => [
            "total_pemasukan"   => $total_in,
            "total_pengeluaran" => $total_out,
            "saldo_kas"         => $total_in - $total_out
        ],
        "pendaftar"    => $pendaftar,
        "kamar"        => $kamar,
        "keuangan"     => $keuangan
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => false,
        "message" => "Gagal mengambil data: " . $e->getMessage()
    ]);
}