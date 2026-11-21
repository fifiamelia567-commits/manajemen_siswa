<?php

require_once "../koneksi.php";

try {
    $keyword = trim($_GET['keyword'] ?? '');

    if ($keyword !== '') {
        $stmt = $pdo->prepare("
            SELECT * FROM siswa
            WHERE nis LIKE :keyword
            OR nama LIKE :keyword
            OR kelas LIKE :keyword
            OR jurusan LIKE :keyword
            ORDER BY id DESC
        ");

        $stmt->execute([
            'keyword' => "%$keyword%"
        ]);
    } else {
        $stmt = $pdo->query("
            SELECT * FROM siswa
            ORDER BY id DESC
        ");
    }

    $data = $stmt->fetchAll();

    responseJson(true, "Data siswa berhasil ditampilkan", $data);

} catch (PDOException $e) {
    responseJson(false, "Gagal mengambil data siswa");
}