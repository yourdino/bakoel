<?php
include 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
    exit;
}

$pesan_konfirmasi = '';

// Ambil data beli dari database untuk pengguna saat ini
$sql_beli = "SELECT beli.*, produk.nama_produk, produk.harga_produk, produk.stok, produk.gambar 
                FROM beli 
                JOIN produk ON beli.id_produk = produk.ID
                WHERE beli.id_user='{$_SESSION['ID']}'";
$result_beli = $is_connect->query($sql_beli);

// Ambil data belanja dari session keranjang
if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
    $keranjang = $_SESSION['keranjang'];
} else {
    // Tidak ada produk dalam keranjang, kembalikan pengguna ke halaman belanja
    header("Location: belanja.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan semua input tersedia
    if (!isset($_POST['metode_pembayaran'])) {
        // Jika tidak, kembalikan pengguna ke halaman checkout
        header("Location: checkout.php");
        exit();
    }

    $id_user = $_SESSION['ID'];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $note = $_POST['catatan'];

    // Menggunakan status unik untuk setiap pesanan
    $status = 'pending_' . md5(uniqid(rand(), true));

// Jika metode pembayaran adalah 'cash'
if ($metode_pembayaran == 'cash') {
    // Simpan data ke tabel beli
    foreach ($keranjang as $id_produk => $item) {
        $id_produk = $item['id_produk'];
        $jumlah_beli = $item['jumlah'];
        $harga = $item['harga_produk'];
        $query = "INSERT INTO beli (id_user, id_produk, jumlah_beli, total_harga, metode_pembayaran, catatan, status) 
                  VALUES ('$id_user', '$id_produk', '$jumlah_beli', '$harga', '$metode_pembayaran', '$note', '$status')";
        if (!mysqli_query($is_connect, $query)) {
            echo "Error: " . mysqli_error($is_connect);
            exit;
        }
    }

    // Mengosongkan keranjang setelah checkout
    unset($_SESSION['keranjang']);

    // Set pesan konfirmasi
    $pesan_konfirmasi = 'Jangan lupa membayar, jika ada uang pas. Terimakasih!';
} elseif ($metode_pembayaran == 'gopay') {
    // Redirect ke halaman pembayaran Gopay
    header("Location: gopay_pembayaran.php");
    exit();
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="./checkout.css">
</head>
<body>
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="belanja.php">Belanja</a></li>
                    <li><a href="pesanan.php">Riwayat</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <a href="./detail.html" class="icon-link">
                <i class='bx bx-cart-alt'></i>
            </a>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>
        
        <div id="form">
            <div class="form-box">
                <div class="form-content">
                    <form id="payment-form" method="POST" action="">
                        <h1 class="judul">Bayar Pesanan</h1>
                        <p>Cek lagi pesananmu dan pastikan pesananmu sudah sesuai</p>
                        <div class="table-content">
                            <table>
                                <tr>
                                    <th> </th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th> </th>
                                </tr>
                                <?php
                                $total_harga = 0;
                                $notes = array();
                                if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
                                    foreach ($_SESSION['keranjang'] as $id_produk => $item) {
                                        $total_harga += $item['harga_produk'] * $item['jumlah'];
                                        // Periksa apakah kunci 'catatan' ada pada item keranjang
                                        $catatan = isset($item['catatan']) ? $item['catatan'] : ''; // Jika tidak ada, gunakan string kosong
                                        $notes[] = $catatan; // Simpan catatan ke dalam array
                                        echo "<tr>
                                                <td><img src='./img/{$item['gambar']}' alt=''></td>
                                                <td class='nama'>{$item['nama_produk']}</td>
                                                <td>{$item['jumlah']}</td>
                                                <td>{$item['harga_produk']}</td>
                                                <td>
                                                    <input type='hidden' name='id_produk[]' value='{$item['id_produk']}'>
                                                    <input type='hidden' name='jumlah[]' value='{$item['jumlah']}'>
                                                    <input type='hidden' name='catatan[]' value='{$catatan}'> <!-- Gunakan variabel $catatan yang sudah diperiksa -->
                                                </td>
                                            </tr>";
                                    }                                    
                                } else {
                                    echo "<tr><td colspan='5'>Tidak ada produk dalam keranjang</td></tr>";
                                }
                                ?>
                                <tr>
                                    <td>Total</td>
                                    <td class="nama"></td>
                                    <td></td>
                                    <td><?php echo $total_harga; ?></td>
                                </tr>
                            </table>
                            <div class="note-content">
                                <div class="note">
                                    <ul>
                                    <?php
                                        // Menampilkan setiap catatan dalam elemen <li>
                                        foreach ($notes as $note) {
                                            if (!empty($note)) {
                                                echo "<li>{$note}</li>";
                                            }
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="metode-bayar">
                            <h4>Metode pembayaran</h4>
                            <div class="bayar-content">
                                <label for="cash">
                                    <input type="radio" id="cash" name="metode_pembayaran" value="cash" required>
                                    Cash
                                </label>
                                <label for="cashless">
                                    <input type="radio" id="cashless" name="metode_pembayaran" value="gopay" required>
                                    Gopay/cashless
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="total_harga" value="<?php echo $total_harga; ?>">
                        <button type="submit" class="btn-byr">Bayar</button>
                    </form>
                    <?php if ($pesan_konfirmasi && $metode_pembayaran == 'cash'): ?>
                        <script>
                        // Tampilkan pesan konfirmasi
                        alert("<?php echo $pesan_konfirmasi; ?>");
                        // Redirect kembali ke halaman belanja setelah pengguna menekan tombol OK pada pesan alert
                        window.location.href = "belanja.php";
                        </script>
                    <?php endif; ?>

                    </div>
                </div>
            </div>
    
            <div id="footer">
                <p class="copyright">&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>
        <script>
            var menuList = document.getElementById("menuList");
    
            menuList.style.maxHeight = "0px";
    
            function togglemenu(){
                if(menuList.style.maxHeight == "0px") {
                    menuList.style.maxHeight = "130px";
                } else {
                    menuList.style.maxHeight = "0px";
                }
            }
        </script>
    </body>
    </html>
    