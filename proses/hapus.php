<?php

require_once "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseJson(false, "Metode request tidak diizinkan");
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    responseJson(false, "ID siswa tidak valid");
}

try {
    $stmt = $pdo->prepare("
        DELETE FROM siswa WHERE id = ?
    ");

    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        responseJson(false, "Data siswa tidak ditemukan");
    }

    responseJson(true, "Data siswa berhasil dihapus");

} catch (PDOException $e) {
    responseJson(false, "Gagal menghapus data siswa");
}