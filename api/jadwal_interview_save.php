<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['namaPelamar']) || empty($data['posisi']) || empty($data['tanggal']) || empty($data['waktu'])) {
        echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi!']);
        exit;
    }
    
    $nama_pelamar = mysqli_real_escape_string($koneksi, $data['namaPelamar']);
    $posisi = mysqli_real_escape_string($koneksi, $data['posisi']);
    $tanggal = mysqli_real_escape_string($koneksi, $data['tanggal']);
    $waktu = mysqli_real_escape_string($koneksi, $data['waktu']);
    $link = mysqli_real_escape_string($koneksi, $data['link'] ?? '');
    $catatan = mysqli_real_escape_string($koneksi, $data['catatan'] ?? '');
    $status = 'DIJADWALKAN';
    
    // Check if pelamar exists in database
    $id_pelamar = null;
    $query_check = mysqli_query($koneksi, "SELECT id FROM pelamar WHERE nama = '$nama_pelamar' AND posisi = '$posisi' LIMIT 1");
    if ($query_check && mysqli_num_rows($query_check) > 0) {
        $row = mysqli_fetch_assoc($query_check);
        $id_pelamar = $row['id'];
    }
    
    // Insert into database - use NULL for id_pelamar if not found
    $id_pelamar_value = $id_pelamar !== null ? "'$id_pelamar'" : "NULL";
    
    $query = "INSERT INTO jadwal_interview 
              (id_pelamar, nama_pelamar, posisi, tanggal_interview, waktu_interview, link_interview, catatan, status_interview) 
VALUES ($id_pelamar_value, '$nama_pelamar', '$posisi', '$tanggal', '$waktu', '$link', '$catatan', '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        $id_jadwal = mysqli_insert_id($koneksi);
        echo json_encode([
            'success' => true, 
            'message' => 'Jadwal interview berhasil ditambahkan!',
            'id' => $id_jadwal
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menambahkan jadwal: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
