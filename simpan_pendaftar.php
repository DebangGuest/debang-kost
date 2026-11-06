<?php
require_once "koneksi.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => false, "message" => "Metode tidak diizinkan."]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$nama_lengkap   = trim($input['nama_lengkap'] ?? '');
$no_wa          = trim($input['no_wa'] ?? '');
$no_wa_ortu     = trim($input['no_wa_ortu'] ?? '');
$tempat_tinggal = trim($input['tempat_tinggal'] ?? '');
$jurusan        = trim($input['jurusan'] ?? '');

if (empty($nama_lengkap) || empty($no_wa) || empty($no_wa_ortu) || empty($tempat_tinggal) || empty($jurusan)) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Semua form wajib diisi."]);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO pendaftar (nama_lengkap, no_wa, no_wa_ortu, tempat_tinggal, jurusan) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nama_lengkap, $no_wa, $no_wa_ortu, $tempat_tinggal, $jurusan]);

    echo json_encode(["status" => true, "message" => "Pendaftaran berhasil tersimpan."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Gagal menyimpan: " . $e->getMessage()]);
}