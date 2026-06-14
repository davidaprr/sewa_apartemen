<?php
// export_excel.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hapsah_room";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Set header agar browser otomatis mengunduh file Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Reservasi_Hapsah_Room.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Output format tabel yang akan dibaca otomatis oleh Excel
echo '<table border="1">';
echo '<tr style="background-color: #cba135; color: white; font-weight: bold; text-align: center;">
        <th>No</th>
        <th>Waktu Booking</th>
        <th>Nama Pelanggan</th>
        <th>No WhatsApp</th>
        <th>NIK KTP</th>
        <th>Lokasi Apartemen</th>
        <th>Tipe Hari</th>
        <th>Durasi Sewa</th>
        <th>Tanggal Check-In</th>
        <th>Total Harga (Rp)</th>
      </tr>';

$sql = "SELECT * FROM reservations ORDER BY waktu_pesan DESC";
$result = $conn->query($sql);
$no = 1;

while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td style="text-align:center;">' . $no++ . '</td>';
    echo '<td>' . $row['waktu_pesan'] . '</td>';
    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
    
    // style mso-number-format:"\@" memaksa Excel membaca data sebagai TEXT agar angka 0 di depan nomor WA tidak hilang
    echo '<td style="mso-number-format:\'\@\';">' . htmlspecialchars($row['no_wa']) . '</td>';
    
    // style mso-number-format:"\@" memaksa Excel membaca data sebagai TEXT agar NIK 16 digit tidak berubah format jadi berantakan
    echo '<td style="mso-number-format:\'\@\';">' . htmlspecialchars($row['nik']) . '</td>';
    
    echo '<td>' . htmlspecialchars($row['lokasi']) . '</td>';
    echo '<td style="text-align:center;">' . ucfirst($row['tipe_hari']) . '</td>';
    echo '<td style="text-align:center;">' . $row['durasi'] . '</td>';
    echo '<td style="text-align:center;">' . $row['tanggal'] . '</td>';
    echo '<td style="text-align:right;">' . $row['total_harga'] . '</td>';
    echo '</tr>';
}
echo '</table>';

$conn->close();
exit;
?>