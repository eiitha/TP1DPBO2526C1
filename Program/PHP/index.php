<?php
// WAJIB: Load class sebelum session_start()
require_once "Film.php";
session_start();

// Proteksi & pembersihan session dari incomplete object
if (isset($_SESSION['daftar_film'])) {
    foreach ($_SESSION['daftar_film'] as $key => $film) {
        if ($film instanceof __PHP_Incomplete_Class) {
            unset($_SESSION['daftar_film'][$key]);
        }
    }
    $_SESSION['daftar_film'] = array_values($_SESSION['daftar_film']);
} else {
    $_SESSION['daftar_film'] = [];
}

// Logika Hapus Data
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $idHapus = $_GET['id'];
    foreach ($_SESSION['daftar_film'] as $key => $film) {
        if ($film->getId() === $idHapus) {
            if (file_exists($film->getGambar())) {
                unlink($film->getGambar());
            }
            unset($_SESSION['daftar_film'][$key]);
            $_SESSION['daftar_film'] = array_values($_SESSION['daftar_film']);
            
            // Trigger Notifikasi Hapus
            $_SESSION['pesan'] = [
                'tipe' => 'danger',
                'teks' => '🗑️ Data film berhasil dihapus!'
            ];
            break;
        }
    }
    header("Location: index.php");
    exit;
}

// Logika Edit Data
$editMode = false;
$filmEdit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] === 'edit' && isset($_GET['id'])) {
    $idEdit = $_GET['id'];
    foreach ($_SESSION['daftar_film'] as $film) {
        if ($film->getId() === $idEdit) {
            $editMode = true;
            $filmEdit = $film;
            break;
        }
    }
}

// Logika Simpan (Tambah / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $genre = $_POST['genre'];
    $durasi = (int)$_POST['durasi'];
    $rating = (float)$_POST['rating'];
    $pathGambarLokal = $_POST['gambar_lama'] ?? '';

    // Upload Gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $dirUpload = "uploads/";
        if (!is_dir($dirUpload)) {
            mkdir($dirUpload, 0777, true);
        }
        
        $namaFile = time() . '_' . basename($_FILES['gambar']['name']);
        $targetFilePath = $dirUpload . $namaFile;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFilePath)) {
            $pathGambarLokal = $targetFilePath;
        }
    }

    if (isset($_POST['is_edit']) && $_POST['is_edit'] === '1') {
        // Update Data
        foreach ($_SESSION['daftar_film'] as $film) {
            if ($film->getId() === $id) {
                $film->setJudul($judul);
                $film->setGenre($genre);
                $film->setDurasi($durasi);
                $film->setRating($rating);
                if (!empty($pathGambarLokal)) {
                    $film->setGambar($pathGambarLokal);
                }
                break;
            }
        }
        // Trigger Notifikasi Update
        $_SESSION['pesan'] = [
            'tipe' => 'warning',
            'teks' => '✏️ Data film "' . htmlspecialchars($judul) . '" berhasil diperbarui!'
        ];
    } else {
        // Tambah Data Baru
        $filmBaru = new Film($id, $judul, $genre, $durasi, $rating, $pathGambarLokal);
        $_SESSION['daftar_film'][] = $filmBaru;
        
        // Trigger Notifikasi Tambah
        $_SESSION['pesan'] = [
            'tipe' => 'success',
            'teks' => '✅ Film baru "' . htmlspecialchars($judul) . '" berhasil ditambahkan!'
        ];
    }

    header("Location: index.php");
    exit;
}

// Logika Cari Data
$keyword = $_GET['cari'] ?? '';
$hasilTampil = $_SESSION['daftar_film'];

if (!empty($keyword)) {
    $hasilTampil = array_filter($_SESSION['daftar_film'], function($film) use ($keyword) {
        return stripos($film->getId(), $keyword) !== false || stripos($film->getJudul(), $keyword) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Bioskop</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --accent-color: #6366f1;
            --accent-hover: #4f46e5;
            --danger-color: #ef4444;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            margin-bottom: 25px;
            text-align: center;
        }

        header h1 {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 5px;
        }

        header p {
            color: var(--text-muted);
        }

        /* Styling Alert/Notifikasi */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 0.95rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        .alert-success {
            background-color: #064e3b;
            color: #a7f3d0;
            border: 1px solid #059669;
        }

        .alert-warning {
            background-color: #78350f;
            color: #fef08a;
            border: 1px solid #ca8a04;
        }

        .alert-danger {
            background-color: #7f1d1d;
            color: #fecaca;
            border: 1px solid #dc2626;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .layout-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }

        .card h3 {
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: #0f172a;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn:hover {
            background-color: var(--accent-hover);
        }

        .btn-cancel {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            margin-top: 8px;
        }

        .btn-cancel:hover {
            background-color: var(--border-color);
            color: #fff;
        }

        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-container input {
            flex: 1;
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: #fff;
        }

        .search-container button {
            width: auto;
            padding: 0 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #0f172a;
            color: var(--text-muted);
            padding: 12px 16px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .poster-img {
            width: 65px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            background-color: var(--border-color);
        }

        .badge-rating {
            background-color: #854d0e;
            color: #fef08a;
        }

        .action-links a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 10px;
            font-weight: 500;
        }

        .action-links a.delete {
            color: var(--danger-color);
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>🎬 Bioskop XXI Management</h1>
        <p>Kelola daftar film bioskop secara cepat dan praktis</p>
    </header>

    <!-- Trigger Banner Notifikasi -->
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-<?= $_SESSION['pesan']['tipe'] ?>">
            <?= $_SESSION['pesan']['teks'] ?>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <div class="layout-grid">
        <!-- Form Tambah / Update -->
        <div class="card">
            <h3><?= $editMode ? '✏️ Edit Data Film' : '➕ Tambah Film Baru' ?></h3>
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <?php if ($editMode): ?>
                    <input type="hidden" name="is_edit" value="1">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($filmEdit->getGambar()) ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>ID Film</label>
                    <input type="text" name="id" placeholder="misal: F01" value="<?= $editMode ? htmlspecialchars($filmEdit->getId()) : '' ?>" <?= $editMode ? 'readonly' : 'required' ?>>
                </div>
                <div class="form-group">
                    <label>Judul Film</label>
                    <input type="text" name="judul" placeholder="Judul film" value="<?= $editMode ? htmlspecialchars($filmEdit->getJudul()) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Genre</label>
                    <input type="text" name="genre" placeholder="Action, Horror, dll" value="<?= $editMode ? htmlspecialchars($filmEdit->getGenre()) : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Durasi (Menit)</label>
                    <input type="number" name="durasi" placeholder="120" value="<?= $editMode ? $filmEdit->getDurasi() : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Rating IMDb / Nilai</label>
                    <input type="number" step="0.1" name="rating" placeholder="8.5" value="<?= $editMode ? $filmEdit->getRating() : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Poster Film (Upload File)</label>
                    <input type="file" name="gambar" accept="image/*" <?= $editMode ? '' : 'required' ?>>
                </div>
                
                <button type="submit" class="btn"><?= $editMode ? 'Update Film' : 'Simpan Film' ?></button>
                <?php if ($editMode): ?>
                    <a href="index.php" class="btn btn-cancel">Batal</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel & Pencarian -->
        <div>
            <form action="index.php" method="GET" class="search-container">
                <input type="text" name="cari" placeholder="Cari berdasarkan ID atau Judul..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" class="btn">Cari</button>
                <?php if (!empty($keyword)): ?>
                    <a href="index.php" class="btn btn-cancel" style="width: auto; padding: 10px 15px; margin: 0;">Reset</a>
                <?php endif; ?>
            </form>

            <div class="card table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Genre</th>
                            <th>Durasi</th>
                            <th>Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($hasilTampil)): ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    Belum ada data film yang tersimpan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($hasilTampil as $film): ?>
                                <tr>
                                    <td>
                                        <?php if ($film->getGambar() && file_exists($film->getGambar())): ?>
                                            <img src="<?= htmlspecialchars($film->getGambar()) ?>" class="poster-img" alt="Poster">
                                        <?php else: ?>
                                            <span class="badge">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= htmlspecialchars($film->getId()) ?></strong></td>
                                    <td><?= htmlspecialchars($film->getJudul()) ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($film->getGenre()) ?></span></td>
                                    <td><?= $film->getDurasi() ?> min</td>
                                    <td><span class="badge badge-rating">⭐ <?= $film->getRating() ?></span></td>
                                    <td class="action-links">
                                        <a href="index.php?aksi=edit&id=<?= urlencode($film->getId()) ?>">Edit</a>
                                        <a href="index.php?aksi=hapus&id=<?= urlencode($film->getId()) ?>" class="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>