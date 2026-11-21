<?php

require_once "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseJson(false, "Metode request tidak diizinkan");
}

$id = (int) ($_POST['id'] ?? 0);
$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if ($id <= 0) {
    responseJson(false, "ID siswa tidak valid");
}

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
        UPDATE siswa SET
            nis = ?,
            nama = ?,
            kelas = ?,
            jurusan = ?,
            alamat = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $nis,
        $nama,
        $kelas,
        $jurusan,
        $alamat,
        $id
    ]);

    if ($stmt->rowCount() === 0) {
        $cek = $pdo->prepare("SELECT id FROM siswa WHERE id = ?");
        $cek->execute([$id]);

        if (!$cek->fetch()) {
            responseJson(false, "Data siswa tidak ditemukan");
        }
    }

    responseJson(true, "Data siswa berhasil diperbarui");

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        responseJson(false, "NIS sudah digunakan siswa lain");
    }

    responseJson(false, "Gagal memperbarui data siswa");
}