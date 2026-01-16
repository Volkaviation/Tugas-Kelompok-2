<?php
// Debug script untuk memeriksa database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Debug Information</h1>";
echo "<hr>";

// Include koneksi
include 'koneksi.php';

// 1. Cek koneksi
echo "<h2>1. Koneksi Database</h2>";
if ($koneksi) {
    echo "✅ <strong>Koneksi BERHASIL</strong><br>";
    echo "Host: localhost<br>";
    echo "Database: db_pelamar_oprek<br><br>";
} else {
    echo "❌ <strong>Koneksi GAGAL:</strong> " . mysqli_connect_error() . "<br><br>";
    die();
}

// 2. Cek jumlah total records
echo "<h2>2. Total Records di Tabel Pelamar</h2>";
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar");
if ($query_total) {
    $result = mysqli_fetch_assoc($query_total);
    echo "✅ <strong>Total Records:</strong> " . $result['total'] . " records<br><br>";
} else {
    echo "❌ <strong>Query Error:</strong> " . mysqli_error($koneksi) . "<br><br>";
}

// 3. Cek breakdown per status_akhir
echo "<h2>3. Breakdown per Status Akhir</h2>";
$query_status = mysqli_query($koneksi, "SELECT status_akhir, COUNT(*) as jumlah FROM pelamar GROUP BY status_akhir ORDER BY jumlah DESC");
if ($query_status) {
    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Status Akhir</th><th>Jumlah</th></tr>";
    $total_check = 0;
    while ($row = mysqli_fetch_assoc($query_status)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['status_akhir']) . "</td>";
        echo "<td>" . $row['jumlah'] . "</td>";
        echo "</tr>";
        $total_check += $row['jumlah'];
    }
    echo "<tr style='background-color: #f0f0f0; font-weight: bold;'>";
    echo "<td>TOTAL</td>";
    echo "<td>" . $total_check . "</td>";
    echo "</tr>";
    echo "</table><br>";
} else {
    echo "❌ <strong>Query Error:</strong> " . mysqli_error($koneksi) . "<br><br>";
}

// 4. Cek beberapa sample data
echo "<h2>4. Sample Data (10 records pertama)</h2>";
$query_sample = mysqli_query($koneksi, "SELECT id, nama, status_akhir, tanggal_melamar FROM pelamar ORDER BY id LIMIT 10");
if ($query_sample) {
    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Status Akhir</th><th>Tanggal Melamar</th></tr>";
    while ($row = mysqli_fetch_assoc($query_sample)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status_akhir']) . "</td>";
        echo "<td>" . $row['tanggal_melamar'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ <strong>Query Error:</strong> " . mysqli_error($koneksi) . "<br><br>";
}

// 5. Cek range ID
echo "<h2>5. Range ID Records</h2>";
$query_range = mysqli_query($koneksi, "SELECT MIN(id) as min_id, MAX(id) as max_id FROM pelamar");
if ($query_range) {
    $result = mysqli_fetch_assoc($query_range);
    echo "Min ID: " . $result['min_id'] . "<br>";
    echo "Max ID: " . $result['max_id'] . "<br><br>";
}

// 6. Cek apakah ada ID yang terlewat
echo "<h2>6. Missing IDs (jika ada)</h2>";
$query_missing = mysqli_query($koneksi, "
    SELECT id + 1 as missing_id
    FROM pelamar mo
    WHERE NOT EXISTS (SELECT NULL FROM pelamar mi WHERE mi.id = mo.id + 1)
    AND id < (SELECT MAX(id) FROM pelamar)
    LIMIT 20
");
if ($query_missing && mysqli_num_rows($query_missing) > 0) {
    echo "⚠️ Ada ID yang hilang:<br>";
    while ($row = mysqli_fetch_assoc($query_missing)) {
        echo "- ID " . $row['missing_id'] . "<br>";
    }
    echo "<br>";
} else {
    echo "✅ Tidak ada ID yang hilang (atau ID sequential)<br><br>";
}

echo "<hr>";
echo "<h2>Kesimpulan:</h2>";
echo "<p>Silakan periksa hasil di atas untuk mengetahui apakah:</p>";
echo "<ul>";
echo "<li>Jumlah total records sesuai dengan yang diharapkan (150)</li>";
echo "<li>Apakah ada data yang tidak masuk ke database saat import</li>";
echo "<li>Apakah ada records dengan status_akhir yang NULL atau kosong</li>";
echo "</ul>";

mysqli_close($koneksi);
?>
