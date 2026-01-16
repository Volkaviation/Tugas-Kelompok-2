<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil nama user dari session
$nama_user = $_SESSION['nama_lengkap'] ?? 'User';

// Query untuk menghitung pendaftar per posisi (top 4)
$query_posisi = mysqli_query($koneksi, "
    SELECT posisi, COUNT(*) as jumlah 
    FROM pelamar 
    GROUP BY posisi 
    ORDER BY jumlah DESC 
    LIMIT 10
");

$posisi_data = [];
$posisi_labels = [];
$posisi_values = [];
while($row = mysqli_fetch_assoc($query_posisi)) {
    $posisi_data[] = $row;
    $posisi_labels[] = $row['posisi'];
    $posisi_values[] = $row['jumlah'];
}

// Query untuk statistik
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar");
$total_pendaftar = mysqli_fetch_assoc($query_total)['total'];

$query_diterima = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Diterima'");
$total_diterima = mysqli_fetch_assoc($query_diterima)['total'];

$query_tidak_lolos = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelamar WHERE status_akhir = 'Tidak Lolos'");
$total_ditolak = mysqli_fetch_assoc($query_tidak_lolos)['total'];

// Hitung proses (selain diterima dan tidak lolos)
$total_proses = $total_pendaftar - $total_diterima - $total_ditolak;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0; /* GARIS HEADER */
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
        
        /* CONTENT GRID */
        .content {
            margin-top: 25px;
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 20px;
        }

        /* CHART CARD */
        .chart-card {
            background: #87aeea;
            border-radius: 10px;
            padding: 20px;
            position: relative;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
        }

        .chart-month {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 13px;
            color: #fff;
        }

        /* RIGHT PANEL */
        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* POSISI CARD */
        .posisi-card {
            background: #000;
            color: #fff;
            border-radius: 16px;
            padding: 18px;
        }

        .posisi-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .posisi-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .posisi-item:last-child {
            border-bottom: none;
        }

        .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #5b5bff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* STATISTIC */
        .stat-card {
            background: #7fa6e8;
            border-radius: 10px;
            padding: 15px;
            color: #fff;
        }

        .stat-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 14px;
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
                <a href="dashboard.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-bookmark"></i></span>
                    <span>Dashboard</span>
                </a>
                <a href="posisi.php" class="nav-item active">
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
            <div class="page-header">
                <div class="welcome">
                    <h1 class="page-title">Welcome, <?php echo htmlspecialchars($nama_user); ?></h1>
                    <p  class="page-subtitle">Showroom Mobil masa kini !!</p>
                </div>
            </div>

        <div class="content">

            <!-- BAR CHART -->
            <div class="chart-card">
                <div class="chart-title">Jumlah Pendaftar per Posisi</div>
                <div class="chart-month">Maret 2025 ⌄</div>
                <canvas id="barChart" height="160"></canvas>
            </div>

            <!-- RIGHT -->
            <div class="right-panel">

                <div class="posisi-card">
                    <div class="posisi-header">
                        <strong>Posisi</strong> ⋮
                    </div>

                    <?php 
                    $icons = ['👤', '⚙️', '🧾', '💻', '📊', '🎨', '📢', '📞'];
                    $icon_index = 0;
                    foreach(array_slice($posisi_data, 0, 4) as $pos) { 
                    ?>
                    <div class="posisi-item">
                        <div class="icon"><?php echo $icons[$icon_index % count($icons)]; ?></div> 
                        <?php echo htmlspecialchars($pos['posisi']); ?>
                    </div>
                    <?php 
                        $icon_index++;
                    } 
                    ?>

                    <div style="margin-top:10px;font-size:13px;">See All ⌄</div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">Statistic</div>
                    <div class="stat-row"><span>Total Pendaftar</span><span><?php echo $total_pendaftar; ?></span></div>
                    <div class="stat-row"><span>Diterima</span><span><?php echo $total_diterima; ?></span></div>
                    <div class="stat-row"><span>Ditolak</span><span><?php echo $total_ditolak; ?></span></div>
                    <div class="stat-row"><span>Proses</span><span><?php echo $total_proses; ?></span></div>
                </div>

            </div>

        </div>
    </main>

</div>

<script>
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($posisi_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($posisi_values); ?>,
            backgroundColor: '#79ecec',
            borderRadius: 6
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
        }
    }
});
</script>

</body>
</html>
