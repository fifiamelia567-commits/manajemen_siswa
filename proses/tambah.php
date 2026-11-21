<?php

require_once "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseJson(false, "Metode request tidak diizinkan");
}

$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if (
    $nis === '' ||
    $nama === '' ||
    $kelas === '' ||
    $jurusan === '' ||
    $alamat === ''
) {
    responseJson(false, "Semua formulir wajib diisi");
}

if (!preg_match('/^[0-9]+$/', $nis)) {
    responseJson(false, "NIS harus berupa angka");
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO siswa (nis, nama, kelas, jurusan, alamat)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $nis,
        $nama,
        $kelas,
        $jurusan,
        $alamat
    ]);

    responseJson(true, "Data siswa berhasil ditambahkan");

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        responseJson(false, "NIS sudah terdaftar");
    }

    responseJson(false, "Gagal menambahkan data siswa");
}