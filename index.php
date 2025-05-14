<?php
$host = "localhost";
$user = "phpmyadmin";
$pass = "1234567890";
$db   = "db_inventory";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$pesan = "";
$edit_barang = null;

// Simpan barang baru
if (isset($_POST['simpan_barang'])) {
    $kode   = $_POST['kode_barang'];
    $nama   = $_POST['nama_barang'];
    $jumlah = intval($_POST['jumlah_barang']);
    $satuan = $_POST['satuan_barang'];
    $harga  = $_POST['harga_beli'];
    $status = ($jumlah > 0) ? 1 : 0;

    $cek = "SELECT * FROM tb_inventory WHERE kode_barang='$kode'";
    $res = $conn->query($cek);
    if ($res->num_rows == 0) {
        $insert = "INSERT INTO tb_inventory (kode_barang, nama_barang, jumlah_barang, satuan_barang, harga_beli, status_barang)
                   VALUES ('$kode', '$nama', $jumlah, '$satuan', $harga, $status)";
        $pesan = $conn->query($insert) ? "Barang berhasil ditambahkan." : "Gagal menambahkan.";
    } else {
        $pesan = "Kode sudah ada, gunakan tambah stok.";
    }
}

// Tambah stok
if (isset($_POST['tambah_stok'])) {
    $kode   = $_POST['kode_barang'];
    $jumlah = intval($_POST['jumlah_barang']);

    $result = $conn->query("SELECT * FROM tb_inventory WHERE kode_barang='$kode'");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $new_jumlah = $data['jumlah_barang'] + $jumlah;
        $status = ($new_jumlah > 0) ? 1 : 0;
        $update = "UPDATE tb_inventory SET jumlah_barang=$new_jumlah, status_barang=$status WHERE kode_barang='$kode'";
        $pesan = $conn->query($update) ? "Stok ditambah." : "Gagal tambah stok.";
    } else {
        $pesan = "Barang tidak ditemukan.";
    }
}

// Pemakaian barang
if (isset($_POST['pakai_barang'])) {
    $kode   = $_POST['kode_barang_pakai'];
    $jumlah_pakai = intval($_POST['jumlah_pakai']);

    $result = $conn->query("SELECT * FROM tb_inventory WHERE kode_barang='$kode'");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if ($data['jumlah_barang'] >= $jumlah_pakai) {
            $sisa = $data['jumlah_barang'] - $jumlah_pakai;
            $status = ($sisa > 0) ? 1 : 0;
            $update = "UPDATE tb_inventory SET jumlah_barang=$sisa, status_barang=$status WHERE kode_barang='$kode'";
            $pesan = $conn->query($update) ? "Barang dipakai." : "Gagal pakai barang.";
        } else {
            $pesan = "Jumlah melebihi stok!";
        }
    } else {
        $pesan = "Barang tidak ditemukan.";
    }
}

// Hapus barang
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $pesan = $conn->query("DELETE FROM tb_inventory WHERE id_barang=$id") ? "Barang dihapus." : "Gagal hapus.";
}

// Edit: ambil data
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM tb_inventory WHERE id_barang=$id");
    $edit_barang = $result->fetch_assoc();
}

// Simpan update
if (isset($_POST['update_barang'])) {
    $id     = $_POST['id_barang'];
    $nama   = $_POST['nama_barang'];
    $jumlah = intval($_POST['jumlah_barang']);
    $satuan = $_POST['satuan_barang'];
    $harga  = $_POST['harga_beli'];
    $status = ($jumlah > 0) ? 1 : 0;

    $update = "UPDATE tb_inventory SET 
                nama_barang='$nama',
                jumlah_barang=$jumlah,
                satuan_barang='$satuan',
                harga_beli=$harga,
                status_barang=$status
               WHERE id_barang=$id";
    $pesan = $conn->query($update) ? "Data berhasil diupdate." : "Gagal update.";
    $edit_barang = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function konfirmasiHapus(id) {
            if (confirm("Yakin hapus barang ini?")) {
                window.location = "?hapus=" + id;
            }
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>Inventory Barang</h2>
    <?php if ($pesan): ?>
        <div class="alert alert-info"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <!-- Form Tambah / Edit Barang -->
    <div class="card mb-3">
        <div class="card-header"><?php echo $edit_barang ? "Edit Barang" : "Tambah Barang Baru"; ?></div>
        <div class="card-body">
            <form method="post">
                <?php if ($edit_barang): ?>
                    <input type="hidden" name="id_barang" value="<?php echo $edit_barang['id_barang']; ?>">
                <?php endif; ?>
                <input type="text" name="kode_barang" class="form-control mb-2"
                       placeholder="Kode Barang" value="<?php echo $edit_barang['kode_barang'] ?? ''; ?>"
                       <?php echo $edit_barang ? 'readonly' : 'required'; ?>>
                <input type="text" name="nama_barang" class="form-control mb-2"
                       placeholder="Nama Barang" value="<?php echo $edit_barang['nama_barang'] ?? ''; ?>" required>
                <input type="number" name="jumlah_barang" class="form-control mb-2"
                       placeholder="Jumlah" value="<?php echo $edit_barang['jumlah_barang'] ?? ''; ?>" required>
                <select name="satuan_barang" class="form-control mb-2">
                    <option value="">-- Pilih Satuan Barang --</option>
                    <?php
                    $satuan = $edit_barang['satuan_barang'] ?? '';
                    foreach (["pcs", "kg", "liter", "meter"] as $val) {
                        $sel = ($satuan === $val) ? "selected" : "";
                        echo "<option value='$val' $sel>$val</option>";
                    }
                    ?>
                </select>
                <input type="number" name="harga_beli" class="form-control mb-2"
                       placeholder="Harga Beli" value="<?php echo $edit_barang['harga_beli'] ?? ''; ?>" required>
                <button type="submit"
                        name="<?php echo $edit_barang ? 'update_barang' : 'simpan_barang'; ?>"
                        class="btn btn-<?php echo $edit_barang ? 'warning' : 'primary'; ?>">
                    <?php echo $edit_barang ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_barang): ?>
                    <a href="?" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tambah Stok -->
    <div class="card mb-3">
        <div class="card-header">Tambah Stok</div>
        <div class="card-body">
            <form method="post">
                <input type="text" name="kode_barang" placeholder="Kode Barang" required class="form-control mb-2">
                <input type="number" name="jumlah_barang" placeholder="Jumlah Tambahan" required class="form-control mb-2">
                <button type="submit" name="tambah_stok" class="btn btn-success">Tambah Stok</button>
            </form>
        </div>
    </div>

    <!-- Pakai Barang -->
    <div class="card mb-3">
        <div class="card-header">Pemakaian Barang</div>
        <div class="card-body">
            <form method="post">
                <input type="text" name="kode_barang_pakai" placeholder="Kode Barang" required class="form-control mb-2">
                <input type="number" name="jumlah_pakai" placeholder="Jumlah Digunakan" required class="form-control mb-2">
                <button type="submit" name="pakai_barang" class="btn btn-danger">Pakai Barang</button>
            </form>
        </div>
    </div>

    <!-- Tabel Barang -->
    <div class="card">
        <div class="card-header bg-secondary text-white">Data Barang</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th><th>Kode</th><th>Nama</th><th>Jumlah</th><th>Satuan</th><th>Harga</th><th>Status</th><th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $result = $conn->query("SELECT * FROM tb_inventory ORDER BY id_barang DESC");
                while ($row = $result->fetch_assoc()) {
                    $status = $row['status_barang'] ? "Available" : "Not Available";
                    echo "<tr>
                        <td>{$row['id_barang']}</td>
                        <td>{$row['kode_barang']}</td>
                        <td>{$row['nama_barang']}</td>
                        <td>{$row['jumlah_barang']}</td>
                        <td>{$row['satuan_barang']}</td>
                        <td>Rp " . number_format($row['harga_beli'], 0, ',', '.') . "</td>
                        <td>$status</td>
                        <td>
                            <a href='?edit={$row['id_barang']}' class='btn btn-sm btn-warning'>Edit</a>
                            <button onclick='konfirmasiHapus({$row['id_barang']})' class='btn btn-sm btn-danger'>Hapus</button>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
