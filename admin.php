<?php
// admin.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hapsah_room";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM reservations ORDER BY waktu_pesan DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Apartemen by Hapsah Room</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@400;500;600&display=swap');
        
        :root {
            --primary: #cba135;
            --dark: #1a1a1a;
            --light: #f9f9f9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f4f4; padding: 40px 20px; }
        
        .admin-container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        .header-admin { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--primary); padding-bottom: 15px; margin-bottom: 25px; }
        .header-admin h1 { font-family: 'Playfair Display', serif; color: var(--dark); font-size: 2rem; }
        
        .btn-export { background-color: #1d6f42; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; border: none; cursor: pointer; }
        .btn-export:hover { background-color: #144d2e; box-shadow: 0 4px 12px rgba(29,111,66,0.3); }
        
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: var(--dark); color: var(--primary); font-weight: 600; }
        tr:hover { background-color: #f9f9f9; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .badge-weekday { background: #e3f2fd; color: #0d47a1; }
        .badge-weekend { background: #fff3e0; color: #e65100; }
    </style>
</head>
<body>

    <div class="admin-container">
        <div class="header-admin">
            <h1>Dashboard Data Reservasi - Hapsah Room</h1>
            <a href="export_excel.php" class="btn-export">📊 Export ke Excel (.xls)</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu Pesan</th>
                        <th>Nama</th>
                        <th>No WA</th>
                        <th>NIK KTP</th>
                        <th>Lokasi Apartemen</th>
                        <th>Tipe Hari</th>
                        <th>Durasi</th>
                        <th>Tanggal Check-In</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while($row = $result->fetch_assoc()) {
                            $badge_class = ($row['tipe_hari'] == 'weekday') ? 'badge-weekday' : 'badge-weekend';
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>".date('d/m/Y H:i', strtotime($row['waktu_pesan']))."</td>
                                    <td><strong>".htmlspecialchars($row['nama'])."</strong></td>
                                    <td>".htmlspecialchars($row['no_wa'])."</td>
                                    <td><code>".htmlspecialchars($row['nik'])."</code></td>
                                    <td>".htmlspecialchars($row['lokasi'])."</td>
                                    <td><span class='badge {$badge_class}'>{$row['tipe_hari']}</span></td>
                                    <td>{$row['durasi']}</td>
                                    <td>".date('d/m/Y', strtotime($row['tanggal']))."</td>
                                    <td><strong>Rp ".number_format($row['total_harga'], 0, ',', '.')."</strong></td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='10' style='text-align:center; padding:30px; color:#888;'>Belum ada data reservasi masuk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<?php $conn->close(); ?>