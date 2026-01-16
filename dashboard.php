<?php
// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Debug: Cek koneksi database
if (!$koneksi) {
    die("KONEKSI GAGAL! Error: " . mysqli_connect_error());
}
echo "<!-- DEBUG: Koneksi database berhasil -->\n";

// Ambil nama user dari session
$nama_user = $_SESSION['nama_lengkap'] ?? 'User';

// Debug: Tampilkan info session
echo "<!-- DEBUG: Nama user dari session = " . htmlspecialchars($nama_user) . " -->\n";

// Query untuk total pelamar
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar");
if (!$query_total) {
    die("Error query total: " . mysqli_error($koneksi));
}
$result_total = mysqli_fetch_assoc($query_total);
$total_pelamar = $result_total['total'] ?? 0;
echo "<!-- DEBUG: Total Pelamar = $total_pelamar -->\n";

// Query untuk status Interview/Wawancara
$query_interview = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Wawancara'");
if (!$query_interview) {
    die("Error query interview: " . mysqli_error($koneksi));
}
$result_interview = mysqli_fetch_assoc($query_interview);
$total_interview = $result_interview['total'] ?? 0;
echo "<!-- DEBUG: Total Interview = $total_interview -->\n";

// Query untuk status Diterima
$query_diterima = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Diterima'");
if (!$query_diterima) {
    die("Error query diterima: " . mysqli_error($koneksi));
}
$result_diterima = mysqli_fetch_assoc($query_diterima);
$total_diterima = $result_diterima['total'] ?? 0;
echo "<!-- DEBUG: Total Diterima = $total_diterima -->\n";

// Query untuk data chart per bulan (Januari - Juni 2024)
$query_chart = mysqli_query($koneksi, "
    SELECT 
        MONTH(tanggal_melamar) as bulan,
        COUNT(*) as jumlah
    FROM pelamar
    WHERE YEAR(tanggal_melamar) = 2024 AND MONTH(tanggal_melamar) BETWEEN 1 AND 6
    GROUP BY MONTH(tanggal_melamar)
    ORDER BY MONTH(tanggal_melamar)
");

// Inisialisasi array untuk 6 bulan
$data_chart = [0, 0, 0, 0, 0, 0];
while($row = mysqli_fetch_assoc($query_chart)) {
    $data_chart[$row['bulan'] - 1] = $row['jumlah']; // Index 0-5 untuk bulan 1-6
}
echo "<!-- DEBUG: Data Chart = " . implode(',', $data_chart) . " -->\n";

// Query untuk pie chart (status seleksi)
$query_tes_tulis = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Tes Tulis'");
if (!$query_tes_tulis) {
    die("Error query tes tulis: " . mysqli_error($koneksi));
}
$result_tes_tulis = mysqli_fetch_assoc($query_tes_tulis);
$total_tes_tulis = $result_tes_tulis['total'] ?? 0;
echo "<!-- DEBUG: Total Tes Tulis = $total_tes_tulis -->\n";

$query_tidak_lolos = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Tidak Lolos'");
if (!$query_tidak_lolos) {
    die("Error query tidak lolos: " . mysqli_error($koneksi));
}
$result_tidak_lolos = mysqli_fetch_assoc($query_tidak_lolos);
$total_tidak_lolos = $result_tidak_lolos['total'] ?? 0;
echo "<!-- DEBUG: Total Tidak Lolos = $total_tidak_lolos -->\n";

// Hitung persentase untuk pie chart
$total_all = $total_pelamar > 0 ? $total_pelamar : 1;
$persen_proses = round(($total_interview + $total_tes_tulis) / $total_all * 100, 1);
$persen_ditolak = round($total_tidak_lolos / $total_all * 100, 1);
$persen_diterima = round($total_diterima / $total_all * 100, 1);
echo "<!-- DEBUG: Persentase - Proses=$persen_proses%, Ditolak=$persen_ditolak%, Diterima=$persen_diterima% -->\n";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Showroom Mobil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #007AFF;
            --secondary: #4A90E2;
            --success: #00FF00;
            --warning: #FFD700;
            --danger: #FF6B6B;
            --light: #F8F9FA;
            --dark: #212529;
            --sidebar-width: 250px;
        }

        body {
            background-color: #f5f5f5;
            color: var(--dark);
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e0e0e0;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 28px;
            color: var(--primary);
            margin-right: 10px;
        }

        .sidebar-nav {
            padding: 20px 0;
            flex-grow: 1;
        }

        .nav-item {
            position: relative;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 
                background-color 0.25s ease,
                color 0.25s ease,
                padding-left 0.25s ease;
        }

        .nav-item:hover {
            background: #f0f0f0;
            padding-left: 26px;
        }

        /* garis kiri animasi */
        .nav-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: var(--primary);
            transition: width 0.25s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            width: 4px;
        }

        .sidebar .nav-item {
            color: #000;
        }

        .sidebar .nav-item {
            text-decoration: none;
        }

        .nav-item:hover {
            background: #f0f0f0;
            border-left: 3px solid var(--primary);
        }

        .nav-item.active {
            background: #e6f0ff;
            border-left: 3px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 25px;
            background: #f8f9fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome {
            max-width: 600px;
        }

        .welcome h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome p {
            font-size: 16px;
            color: #555;
        }

        /* CARDS */
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #0b5cff;
            color: white;
            padding: 20px;
            width: 200px;
            border-radius: 12px;
        }

        .card h2 {
            font-size: 32px;
            margin-top: 5px;
        }

        /* CHART AREA */
        .chart-container {
            display: flex;
            gap: 30px;
        }

        .line-chart {
            width: 60%;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .pie-chart {
            width: 40%;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .chart-title {
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #0066cc;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-header, .nav-item span, .sidebar-footer {
                display: none;
            }
            
            .nav-icon {
                margin-right: 0;
                font-size: 18px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 60px;
            }   
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="asset/home.png" alt="Logo" width="60" height="60">
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <span class="nav-icon"><i class="fas fa-bookmark"></i></span>
                    <span>Dashboard</span>
                </a>
                <a href="posisi.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-file"></i></span>
                    <span>Posisi</span>
                </a>
                <a href="jadwal_interview.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-clock"></i></span>
                    <span>Jadwal Interview</span>
                </a>
                <a href="datapelamar.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-database"></i></span>
                    <span>Data Pelamar</span>
                </a>
            </nav>
            
            <div class="sidebar-footer-nav">
                <a href="settings.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span>Settings</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="header">
                <div class="welcome">
                    <h1>Welcome, <?php echo htmlspecialchars($nama_user); ?></h1>
                    <p>Showroom Mobil masa kini !!</p>
                </div>
            </div>
        <!-- STAT CARD -->
        <div class="cards">
            <div class="card">
                <p>Total Pelamar</p>
                <h2><?php echo $total_pelamar; ?></h2>
            </div>
            <div class="card">
                <p>Interview</p>
                <h2><?php echo $total_interview; ?></h2>
            </div>
            <div class="card">
                <p>Diterima</p>
                <h2><?php echo $total_diterima; ?></h2>
            </div>
            <div class="card">
                <p>Total Karyawan</p>
                <h2><?php echo $total_diterima; ?></h2>
            </div>
        </div>

        <!-- CHART -->
        <div class="chart-container">
            <div class="line-chart">
                <div class="chart-title">Januari - Juni (Month)</div>
                <canvas id="lineChart"></canvas>
            </div>

            <div class="pie-chart">
                <div class="chart-title">Status Seleksi</div>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </main>

</div>

<script>
// LINE CHART
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Januari','Februari','Maret','April','Mei','Juni'],
        datasets: [{
            data: [<?php echo implode(',', $data_chart); ?>],
            borderColor: '#6ee7e7',
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        plugins: { legend: { display: false } }
    }
});

// PIE CHART
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Proses','Ditolak','Diterima'],
        datasets: [{
            data: [<?php echo $persen_proses; ?>,<?php echo $persen_ditolak; ?>,<?php echo $persen_diterima; ?>],
            backgroundColor: ['#2f8ab8','#43bcd6','#6ee7e7']
        }]
    }
});
</script>

</body>
</html>
