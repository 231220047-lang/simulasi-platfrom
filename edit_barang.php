<?php
// Izinkan akses dari semua origin (untuk pengembangan lokal)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

// ============================================================
// GET - Ambil semua barang atau 1 barang berdasarkan ID
// ============================================================
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = mysqli_query($conn, "SELECT * FROM barang WHERE id = $id");
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data ? $data : ["status" => "error", "message" => "Barang tidak ditemukan"]);
    } else {
        $result = mysqli_query($conn, "SELECT * FROM barang ORDER BY id DESC");
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

// ============================================================
// POST - Tambah barang baru
// ============================================================
elseif ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $nama  = mysqli_real_escape_string($conn, $input['nama_barang']);
    $harga = intval($input['harga']);

    $query = mysqli_query($conn, "INSERT INTO barang (nama_barang, harga) VALUES ('$nama', $harga)");
    if ($query) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil ditambahkan", "id" => mysqli_insert_id($conn)]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}

// ============================================================
// PUT - Update barang berdasarkan ID
// ============================================================
elseif ($method === 'PUT') {
    $input = json_decode(file_get_contents("php://input"), true);
    $id    = intval($input['id']);
    $nama  = mysqli_real_escape_string($conn, $input['nama_barang']);
    $harga = intval($input['harga']);

    $query = mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', harga=$harga WHERE id=$id");
    if ($query) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil diupdate"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}

// ============================================================
// DELETE - Hapus barang berdasarkan ID
// ============================================================
elseif ($method === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);
    $id    = intval($input['id']);

    $query = mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
    if ($query) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil dihapus"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>
