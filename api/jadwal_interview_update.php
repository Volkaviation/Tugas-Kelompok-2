<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['id_jadwal']) || empty($data['namaPelamar']) || empty($data['posisi']) || empty($data['tanggal']) || empty($data['waktu'])) {
        echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi!']);
        exit;
    }
    
    $id_jadwal = (int)$data['id_jadwal'];
    $nama_pelamar = mysqli_real_escape_string($koneksi, $data['namaPelamar']);
    $posisi = mysqli_real_escape_string($koneksi, $data['posisi']);
    $tanggal = mysqli_real_escape_string($koneksi, $data['tanggal']);
    $waktu = mysqli_real_escape_string($koneksi, $data['waktu']);
    $link = mysqli_real_escape_string($koneksi, $data['link'] ?? '');
    $catatan = mysqli_real_escape_string($koneksi, $data['catatan'] ?? '');
    $status = mysqli_real_escape_string($koneksi, $data['status_interview'] ?? 'DIJADWALKAN');
    
    // Check if pelamar exists in database (for id_pelamar update)
    $id_pelamar = null;
    $query_check = mysqli_query($koneksi, "SELECT id FROM pelamar WHERE nama = '$nama_pelamar' AND posisi = '$posisi' LIMIT 1");
    if ($query_check && mysqli_num_rows($query_check) > 0) {
        $row = mysqli_fetch_assoc($query_check);
        $id_pelamar = $row['id'];
    }
    
    $id_pelamar_value = $id_pelamar !== null ? "'$id_pelamar'" : "NULL";
    
    // Update database
    $query = "UPDATE jadwal_interview SET 
              id_pelamar = $id_pelamar_value,
              nama_pelamar = '$nama_pelamar',
              posisi = '$posisi',
              tanggal_interview = '$tanggal',
              waktu_interview = '$waktu',
              link_interview = '$link',
              catatan = '$catatan',
              status_interview = '$status'
              WHERE id_jadwal = $id_jadwal";
    
    if (mysqli_query($koneksi, $query)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Jadwal interview berhasil diupdate!'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal mengupdate jadwal: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
