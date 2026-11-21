const form = document.getElementById("formSiswa");
const tabel = document.getElementById("tabelSiswa");
const pencarian = document.getElementById("pencarian");
const pesan = document.getElementById("pesan");

let dataSiswa = [];

// Mengamankan teks sebelum ditampilkan ke HTML
function escapeHTML(text) {
    return String(text).replace(/[&<>"']/g, function (char) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;"
        }[char];
    });
}

// Menampilkan pesan
function tampilPesan(message, success = true) {
    pesan.textContent = message;
    pesan.className = success ? "success" : "error";
}

// Mengambil data siswa menggunakan Fetch API
async function tampilData(keyword = "") {
    try {
        const response = await fetch(
            "proses/tampil.php?keyword=" + encodeURIComponent(keyword)
        );

        const result = await response.json();

        if (!result.status) {
            throw new Error(result.message);
        }

        dataSiswa = result.data;
        tabel.innerHTML = "";

        if (dataSiswa.length === 0) {
            tabel.innerHTML = `
                <tr>
                    <td colspan="7">Data siswa tidak ditemukan</td>
                </tr>
            `;
            return;
        }

        dataSiswa.forEach((siswa, index) => {
            tabel.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapeHTML(siswa.nis)}</td>
                    <td>${escapeHTML(siswa.nama)}</td>
                    <td>${escapeHTML(siswa.kelas)}</td>
                    <td>${escapeHTML(siswa.jurusan)}</td>
                    <td>${escapeHTML(siswa.alamat)}</td>
                    <td>
                        <button class="btn-edit"
                            data-id="${Number(siswa.id)}">
                            Edit
                        </button>

                        <button class="btn-hapus"
                            data-id="${Number(siswa.id)}">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        tampilPes(error.message, false);
    }
}

// Mengirim data ke PHP
async function kirimData(url, data) {
    const response = await fetch(url, {
        method: "POST",
        body: data
    });

    const result = await response.json();

    if (!response.ok || !result.status) {
        throw new Error(result.message || "Terjadi kesalahan");
    }

    return result;
}

// Validasi formulir
function validasiForm() {
    const fields = [
        {
            id: "nis",
            error: "errorNis",
            message: "NIS wajib diisi"
        },
        {
            id: "nama",
            error: "errorNama",
            message: "Nama wajib diisi"
        },
        {
            id: "kelas",
            error: "errorKelas",
            message: "Kelas wajib diisi"
        },
        {
            id: "jurusan",
            error: "errorJurusan",
            message: "Jurusan wajib diisi"
        },
        {
            id: "alamat",
            error: "errorAlamat",
            message: "Alamat wajib diisi"
        }
    ];

    let valid = true;

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const error = document.getElementById(field.error);

        let message = "";

        if (input.value.trim() === "") {
            message = field.message;
        } else if (
            field.id === "nis" &&
            !/^[0-9]+$/.test(input.value.trim())
        ) {
            message = "NIS harus berupa angka";
        }

        error.textContent = message;
        input.classList.toggle("invalid", message !== "");

        if (message !== "") {
            valid = false;
        }
    });

    return valid;
}

// Validasi secara real-time
form.querySelectorAll("input, textarea").forEach(input => {
    if (input.type === "hidden") return;

    input.addEventListener("input", validasiForm);
});

// Menambahkan atau mengubah data
form.addEventListener("submit", async function (event) {
    event.preventDefault();

    if (!validasiForm()) {
        tampilPesan("Periksa kembali formulir!", false);
        return;
    }

    const id = document.getElementById("id").value;

    const url = id
        ? "proses/edit.php"
        : "proses/tambah.php";

    const formData = new FormData(form);

    try {
        const result = await kirimData(url, formData);

        tampilPesan(result.message);

        form.reset();
        document.getElementById("id").value = "";

        document.getElementById("btnSimpan").textContent =
            "Simpan Data";

        document.getElementById("btnBatal").style.display =
            "none";

        document.querySelectorAll("small").forEach(element => {
            element.textContent = "";
        });

        form.querySelectorAll("input, textarea").forEach(input => {
            input.classList.remove("invalid");
        });

        tampilData(pencarian.value);

    } catch (error) {
        tampilPesan(error.message, false);
    }
});

// Event Delegation untuk tombol edit dan hapus
tabel.addEventListener("click", async function (event) {

    const id = event.target.dataset.id;

    // Tombol edit
    if (event.target.classList.contains("btn-edit")) {

        const siswa = dataSiswa.find(item =>
            Number(item.id) === Number(id)
        );

        if (!siswa) return;

        document.getElementById("id").value = siswa.id;
        document.getElementById("nis").value = siswa.nis;
        document.getElementById("nama").value = siswa.nama;
        document.getElementById("kelas").value = siswa.kelas;
        document.getElementById("jurusan").value = siswa.jurusan;
        document.getElementById("alamat").value = siswa.alamat;

        document.getElementById("btnSimpan").textContent =
            "Update Data";

        document.getElementById("btnBatal").style.display =
            "inline-block";

        validasiForm();

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    // Tombol hapus
    if (event.target.classList.contains("btn-hapus")) {

        const yakin = confirm(
            "Apakah Anda yakin ingin menghapus data siswa ini?"
        );

        if (!yakin) return;

        const formData = new FormData();
        formData.append("id", id);

        try {
            const result = await kirimData(
                "proses/hapus.php",
                formData
            );

            tampilPesan(result.message);
            tampilData(pencarian.value);

        } catch (error) {
            tampilPesan(error.message, false);
        }
    }

});

// Tombol batal edit
document.getElementById("btnBatal").addEventListener("click", function () {

    form.reset();

    document.getElementById("id").value = "";

    document.getElementById("btnSimpan").textContent =
        "Simpan Data";

    this.style.display = "none";

    document.querySelectorAll("small").forEach(element => {
        element.textContent = "";
    });

    form.querySelectorAll("input, textarea").forEach(input => {
        input.classList.remove("invalid");
    });

    tampilPesan("");
});

// Debouncing pencarian
let timerPencarian;

pencarian.addEventListener("input", function () {

    clearTimeout(timerPencarian);

    timerPencarian = setTimeout(() => {
        tampilData(this.value.trim());
    }, 400);

});

// Menampilkan data ketika halaman dibuka
document.getElementById("btnBatal").style.display = "none";

tampilData();