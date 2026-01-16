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
    
    if (empty($data['nama_posisi'])) {
        echo json_encode(['success' => false, 'message' => 'Nama posisi wajib diisi!']);
        exit;
    }
    
    $nama_posisi = mysqli_real_escape_string($koneksi, $data['nama_posisi']);
    $deskripsi = mysqli_real_escape_string($koneksi, $data['deskripsi'] ?? '');
    $requirements = mysqli_real_escape_string($koneksi, $data['requirements'] ?? '');
    $jumlah = (int)($data['jumlah_dibutuhkan'] ?? 1);
    $status = mysqli_real_escape_string($koneksi, $data['status'] ?? 'AKTIF');
    
    // Check if this is an update or insert
    if (!empty($data['id_posisi'])) {
        // Update existing
        $id = (int)$data['id_posisi'];
        $query = "UPDATE posisi_lowongan SET 
                  nama_posisi = '$nama_posisi',
                  deskripsi = '$deskripsi',
                  requirements = '$requirements',
                  jumlah_dibutuhkan = $jumlah,
                  status = '$status'
                  WHERE id_posisi = $id";
        $message = 'Posisi berhasil diupdate!';
    } else {
        // Insert new
        $query = "INSERT INTO posisi_lowongan (nama_posisi, deskripsi, requirements, jumlah_dibutuhkan, status) 
                  VALUES ('$nama_posisi', '$deskripsi', '$requirements', $jumlah, '$status')";
        $message = 'Posisi berhasil ditambahkan!';
    }
    
    if (mysqli_query($koneksi, $query)) {
        $id = isset($id) ? $id : mysqli_insert_id($koneksi);
        echo json_encode([
            'success' => true, 
            'message' => $message,
            'id' => $id
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menyimpan posisi: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
