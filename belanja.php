<?php
include 'config.php';

session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
    exit;
}

// Inisialisasi keranjang belanja dalam session jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Menambahkan item ke keranjang
if (isset($_POST['beli'])) {
    $id_produk = $_POST['ID']; // Mengambil ID produk dari form
    $stok = 1; // Sesuaikan jumlah sesuai kebutuhan

    // Memeriksa apakah ID produk valid
    if ($id_produk === null || !is_numeric($id_produk) || $id_produk <= 0) {
        echo "ID produk tidak valid.";
        exit;
    }

    // Memeriksa apakah produk sudah ada di keranjang
    if (isset($_SESSION['keranjang'][$id_produk])) {
        // Jika produk sudah ada, update jumlah
        $_SESSION['keranjang'][$id_produk]['jumlah'] += $stok;
    } else {
        // Jika produk belum ada, tambahkan produk ke keranjang
        $sql_produk = "SELECT * FROM produk WHERE ID='$id_produk'";
        $result_produk = $is_connect->query($sql_produk);
        if ($result_produk && $result_produk->num_rows > 0) {
            $row_produk = $result_produk->fetch_assoc();
            $_SESSION['keranjang'][$id_produk] = [
                'nama_produk' => $row_produk['nama_produk'],
                'harga_produk' => $row_produk['harga_produk'],
                'jumlah' => $stok
            ];
        } else {
            echo "Produk tidak ditemukan.";
            exit;
        }
    }
}

// Mengambil produk dari database
$sql_produk = "SELECT * FROM produk";
$result_produk = $is_connect->query($sql_produk);

if (!$result_produk) {
    echo "Terjadi kesalahan saat mengambil produk.";
    exit;
}

// Menghitung jumlah pembelian untuk pengguna saat ini
$id_user = $_SESSION['ID'];
$sql_jumlah_pembelian = "SELECT COUNT(*) AS total_pembelian FROM beli WHERE id_user='$id_user'";
$result_jumlah_pembelian = $is_connect->query($sql_jumlah_pembelian);
if ($result_jumlah_pembelian) {
    $row_jumlah_pembelian = $result_jumlah_pembelian->fetch_assoc();
    $x = $row_jumlah_pembelian['total_pembelian'] + 1; // Nomor pembelian berikutnya
} else {
    $x = 1; // Jika tidak ada pembelian sebelumnya
}

// Menangani tombol bayar
if (isset($_POST['bayar'])) {
    $_SESSION['checkout'] = $_SESSION['keranjang'];
    header("Location: checkout.php");
    exit;
}

$is_connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Belanja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="belanja.css">
</head>
<body>
    
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="./home.php">Home</a></li>
                    <li><a href="./belanja.php">Belanja</a></li>
                    <li><a href="./history.php">Riwayat</a></li>
                    <li><a href="./profile.php">Profile</a></li>
                </ul>
            </nav>
            <a href="./detail.php" class="icon-link">
                <i class='bx bx-cart-alt'></i>
            </a>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>
        <div id="search-bar">
            <div class="search-container">
                <form action="" method="GET" class="search-form">
                <input type="text" id="searchInput" placeholder="Search..." name="search" class="search-input">
                  <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </div>
        </div>

        <div id="user-profile">
            <div class="user-box">
                <div class="user-content">
                    <div class="text-content">
                    <h4><?php echo $_SESSION['username']; ?></h4>
                    <p><?php echo $_SESSION['email']; ?></p>
                    </div>
                    <div class="user-logo"><img src="./img/user (3).png" class="profile"></div>
                </div>
            </div>
            <div class="btn-logout">
                <button><a href="logout.php">Logout</a></button>
            </div>
        </div>
        <div id="keranjang">
            <div class="table-box">
                <div class="table-title">
                    <h2>Keranjang</h2>
                    <p>pembelian ke-<?php echo $x; ?></p> <!-- Nomor pembelian berikutnya --> 
                </div>
                <table>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                    <?php
                if (!empty($_SESSION['keranjang'])) {
                    $total = 0;
                    foreach ($_SESSION['keranjang'] as $item) {
                        echo "<tr>
                                <td>{$item['nama_produk']}</td>
                                <td>{$item['jumlah']}</td>
                                <td>{$item['harga_produk']}</td>
                              </tr>";
                        $total += $item['jumlah'] * $item['harga_produk'];
                    }
                    echo "<tr>
                            <td>Total</td>
                            <td></td>
                            <td>{$total}</td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='3'>Keranjang kosong</td></tr>";
                }
                ?>
                </table>
                <form method="post">
                    <button type="submit" name="bayar" class="btn-table">Bayar</button>
                </form>
            </div>
        </div>
        <div id="produk">
            <div class="produk-list">
                <?php
                if ($result_produk->num_rows > 0) {
                    while ($row = $result_produk->fetch_assoc()) {
                        echo "<div class='produk-box'>
                                <div class='produk-img'>
                                    <img src='./img/{$row['gambar']}' alt=''>
                                </div>
                                <div class='produk-content'>
                                    <div class='produk-desc'>
                                        <h4>{$row['nama_produk']}</h4>
                                        <p class='harga'>Rp {$row['harga_produk']}</p>
                                        <p class='stok'>stok : {$row['stok']}</p>
                                        <p class='id_toko'>{$row['id_toko']}</p>
                                        <p class='waktu_posting'>{$row['waktu_posting']}</p>
                                        <form method='post' action='detail.php'>
                                            <input type='hidden' name='ID' value='{$row['ID']}'>
                                            <button type='submit' name='beli' class='btn'>Detail</button>
                                        </form>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<p>Belum ada produk yang ditambahkan</p>";
                }
                ?>
            </div>
        </div>
        <div id="footer">
            <p class="copyright">&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>
</body>
<script>
    // Fungsi untuk melakukan pencarian saat pengguna mengetikkan query
    function cariProduk() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var produk = document.getElementsByClassName('produk-box');

        for (var i = 0; i < produk.length; i++) {
            var namaProduk = produk[i].getElementsByClassName('produk-desc')[0].getElementsByTagName('h4')[0];
            var nama = namaProduk.textContent.toLowerCase();
            if (nama.indexOf(input) > -1) {
                produk[i].style.display = "";
            } else {
                produk[i].style.display = "none";
            }
        }
    }

    document.getElementById('searchInput').addEventListener('input', cariProduk);

// Tambahkan event listener untuk memanggil fungsi pencarian saat inputan pencarian berubah
document.getElementById('searchInput').addEventListener('input', cariProduk);

    var menuList = document.getElementById("menuList");

    menuList.style.maxHeight = "0px";

    function togglemenu() {
        if (menuList.style.maxHeight == "0px") {
            menuList.style.maxHeight = "200px";
        } else {
            menuList.style.maxHeight = "0px";
        }
    }
</script>
</html>
