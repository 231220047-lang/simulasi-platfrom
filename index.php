<?php
include 'koneksi.php';

// Logika Tambah & Update
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];

    if ($id != "") {
        mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', harga='$harga' WHERE id=$id");
    } else {
        mysqli_query($conn, "INSERT INTO barang (nama_barang, harga) VALUES ('$nama', '$harga')");
    }
    header("Location: index.php");
}

// Logika Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
    header("Location: index.php");
}

// Ambil data untuk Edit
$e_id = ""; $e_nama = ""; $e_harga = "";
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM barang WHERE id=$id");
    $data = mysqli_fetch_array($res);
    $e_id = $data['id'];
    $e_nama = $data['nama_barang'];
    $e_harga = $data['harga'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppToko Modern - Inventaris</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #0b0e14;
            --bg-card: rgba(255, 255, 255, 0.03);
            --accent-pink: #ff2e63;
            --accent-blue: #08d9d6;
            --text-main: #eaeaea;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            background-image: radial-gradient(circle at 50% -20%, #1e293b, #0b0e14);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        /* Header Style */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand h1 {
            font-size: 24px;
            letter-spacing: 2px;
            font-weight: 700;
            background: linear-gradient(to right, #08d9d6, #ff2e63);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand p { font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin-top: 4px; }

        .live-badge {
            background: rgba(8, 217, 214, 0.1);
            color: var(--accent-blue);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid var(--accent-blue);
        }

        /* Card Glassmorphism */
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .card h3 { font-size: 16px; margin-bottom: 20px; color: var(--accent-blue); display: flex; align-items: center; gap: 10px; }

        /* Form Controls */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; }

        input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: var(--accent-blue); box-shadow: 0 0 10px rgba(8, 217, 214, 0.2); }

        .btn-simpan {
            background: var(--accent-pink);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-simpan:hover { transform: translateY(-2px); opacity: 0.9; }

        /* Table Style */
        table { width: 100%; border-collapse: collapse; }
        
        th {
            text-align: left;
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        td { padding: 20px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 14px; }

        .price { font-weight: 600; color: #ffbc11; }

        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            background: rgba(0, 255, 136, 0.1);
            color: #00ff88;
        }

        .actions { display: flex; gap: 10px; }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-edit { border: 1px solid var(--accent-blue); color: var(--accent-blue); }
        .btn-edit:hover { background: var(--accent-blue); color: #000; }

        .btn-hapus { border: 1px solid #444; color: #ff5f5f; }
        .btn-hapus:hover { background: #ff5f5f; color: white; }

        /* Responsif */
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .btn-simpan { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="brand">
            <h1>APPTOKO</h1>
            <p>Sistem Manajemen Inventaris v2.0</p>
        </div>
        <div class="live-badge">SYSTEM ACTIVE</div>
    </div>

    <div class="card">
        <h3><span>✦</span> Tambah / Edit Barang</h3>
        <form method="POST" class="form-grid">
            <input type="hidden" name="id" value="<?= $e_id ?>">
            <input type="text" name="nama_barang" placeholder="Nama Produk" value="<?= $e_nama ?>" required>
            <input type="number" name="harga" placeholder="Harga (Rp)" value="<?= $e_harga ?>" required>
            <button type="submit" name="simpan" class="btn-simpan">SIMPAN DATA</button>
        </form>
    </div>

    <div class="card">
        <h3><span>✦</span> Daftar Barang Terbaru</h3>
        <table>
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM barang ORDER BY id DESC");
                while ($row = mysqli_fetch_array($query)) {
                    echo "<tr>
                        <td style='color:#666'>#{$row['id']}</td>
                        <td style='font-weight:500'>{$row['nama_barang']}</td>
                        <td class='price'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                        <td><span class='status-pill'>Tersedia</span></td>
                        <td>
                            <div class='actions'>
                                <a href='?edit={$row['id']}' class='btn-action btn-edit'>EDIT</a>
                                <a href='?hapus={$row['id']}' class='btn-action btn-hapus' onclick='return confirm(\"Hapus barang ini?\")'>HAPUS</a>
                            </div>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>