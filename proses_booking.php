<?php
// proses_booking.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hapsah_room";

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// PERBAIKAN: Menggunakan "if", bukan "jika"
if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Memproses data jika formulir dikirim menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form HTML
    $nama        = $_POST['nama'];
    $no_wa       = $_POST['no_wa'];
    $nik         = $_POST['nik'];
    $lokasi      = $_POST['lokasi'];
    $tipe_hari   = $_POST['tipe_hari'];
    $durasi      = $_POST['durasi'];
    $tanggal     = $_POST['tanggal'];
    $total_harga = $_POST['total_harga'];

    // Amankan data dari karakter aneh (SQL Injection) sebelum dimasukkan ke database
    $nama_clean  = $conn->real_escape_string($nama);
    $no_wa_clean = $conn->real_escape_string($no_wa);
    $nik_clean   = $conn->real_escape_string($nik);
    $lokasi_clean= $conn->real_escape_string($lokasi);

    // Query untuk menyimpan data ke tabel reservations
    $sql = "INSERT INTO reservations (nama, no_wa, nik, lokasi, tipe_hari, durasi, tanggal, total_harga) 
            VALUES ('$nama_clean', '$no_wa_clean', '$nik_clean', '$lokasi_clean', '$tipe_hari', '$durasi', '$tanggal', '$total_harga')";

    if ($conn->query($sql) === TRUE) {
        // Format pesan rapi untuk dikirimkan ke WhatsApp Admin Hapsah Room
        $pesan_wa = "Halo Hapsah Room!%0ASaya ingin booking:%0A%0A*Nama:* $nama%0A*No WA:* $no_wa%0A*NIK:* $nik%0A*Lokasi:* $lokasi%0A*Tanggal:* $tanggal%0A*Tipe Hari:* $tipe_hari%0A*Durasi:* $durasi%0A*Estimasi Harga:* Rp " . number_format($total_harga, 0, ',', '.') . "%0A%0AMohon info ketersediaannya, terima kasih!";
        
        // Munculkan notifikasi sukses lalu arahkan otomatis ke WhatsApp admin
        echo "<script>
                alert('Booking berhasil dicatat di database! Menghubungkan ke WhatsApp Admin...');
                window.location.href = 'https://wa.me/6288293795625?text=$pesan_wa';
              </script>";
    } else {
        // Jika query gagal, tampilkan error-nya di mana
        echo "Gagal menyimpan data: " . $conn->error;
    }
}

// Tutup koneksi database jika selesai
$conn->close();
?>