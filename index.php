<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Siswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <header>
        <h1>Manajemen Data Siswa</h1>
        <p>Aplikasi Pengelolaan Data Siswa Berbasis PHP dan AJAX</p>
    </header>

    <section class="card">
        <h2>Form Data Siswa</h2>

        <form id="formSiswa" novalidate>

            <input type="hidden" id="id" name="id">

            <div class="form-group">
                <label>NIS</label>
                <input type="text" id="nis" name="nis"
                       placeholder="Masukkan NIS">
                <small id="errorNis"></small>
            </div>

            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" id="nama" name="nama"
                       placeholder="Masukkan nama siswa">
                <small id="errorNama"></small>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" id="kelas" name="kelas"
                       placeholder="Contoh: XII RPL 1">
                <small id="errorKelas"></small>
            </div>

            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" id="jurusan" name="jurusan"
                       placeholder="Masukkan jurusan">
                <small id="errorJurusan"></small>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea id="alamat" name="alamat"
                          placeholder="Masukkan alamat"></textarea>
                <small id="errorAlamat"></small>
            </div>

            <button type="submit" id="btnSimpan">
                Simpan Data
            </button>

            <button type="button" id="btnBatal" class="btn-secondary">
                Batal
            </button>

        </form>
    </section>

    <section class="card">

        <h2>Daftar Data Siswa</h2>

        <input type="text" id="pencarian"
               placeholder="Cari NIS, nama, kelas, atau jurusan...">

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="tabelSiswa">
                    <tr>
                        <td colspan="7">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p id="pesan"></p>

    </section>

</div>

<script src="js/script.js"></script>

</body>
</html>