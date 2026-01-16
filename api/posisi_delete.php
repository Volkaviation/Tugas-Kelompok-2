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
    
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID posisi tidak valid']);
        exit;
    }
    
    $id = (int)$data['id'];
    
    $query = "DELETE FROM posisi_lowongan WHERE id_posisi = $id";
    
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['success' => true, 'message' => 'Posisi berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus posisi: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
