<?php
include 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
}

$id_user = $_SESSION['ID'];
//echo "ID User: " . htmlspecialchars($id_user) . "<br>";

// Validasi id_user di tabel user
$sql_check_user = "SELECT * FROM user WHERE ID='$id_user'";
$result_check_user = $is_connect->query($sql_check_user);

if ($result_check_user->num_rows == 0) {
    die("Error: User dengan ID tersebut tidak ada.");
}

// Ambil data produk berdasarkan ID dari URL
$id_produk = isset($_POST['ID']) ? $_POST['ID'] : null;

// Lakukan sanitasi pada ID produk untuk menghindari serangan SQL injection
$id_produk = mysqli_real_escape_string($is_connect, $id_produk);

$sql_produk = "SELECT * FROM produk WHERE ID='$id_produk'";
$result_produk = $is_connect->query($sql_produk);

if ($result_produk->num_rows > 0) {
    $produk = $result_produk->fetch_assoc();
} else {
    die("Error: Produk tidak ditemukan di database!");
}

// Menambahkan item ke session keranjang atau redirect ke checkout.php
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id_produk = $_POST['ID'];
    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 1; // default jumlah = 1
    $note = isset($_POST['note']) ? $_POST['note'] : ''; // default catatan = ''

    // Memeriksa apakah produk sudah ada di beli
    $sql_check = "SELECT * FROM beli WHERE id_produk='$id_produk' AND id_user='$id_user'";
    $result_check = $is_connect->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Jika produk sudah ada, update jumlah
        $sql_update = "UPDATE beli SET jumlah_beli = jumlah_beli + $jumlah, catatan = '$note' WHERE id_produk='$id_produk' AND id_user='$id_user'";
        $is_connect->query($sql_update);
    } else {
        // Jika produk belum ada, tambahkan produk ke beli
        $sql_insert = "INSERT INTO beli (id_user, id_produk, jumlah_beli, catatan) VALUES ('$id_user', '$id_produk', '$jumlah', '$note')";
        $is_connect->query($sql_insert);
    }

// Setelah memastikan jumlah produk valid
$_SESSION['keranjang'][$produk['ID']] = [
    'id_produk' => $produk['ID'],
    'nama_produk' => $produk['nama_produk'],
    'harga_produk' => $produk['harga_produk'],
    'jumlah' => $jumlah,
    'gambar' => $produk['gambar'],
];
header("Location: checkout.php");
exit();

}
$is_connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : <?php echo htmlspecialchars($produk['nama_produk']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="./detail.css">
    <link rel="stylesheet" href="./note.css">
</head>
<body>
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li class="nav-li"><a href="home.php">Home</a></li>
                    <li class="nav-li"><a href="belanja.php">Belanja</a></li>
                    <li class="nav-li"><a href="pesanan.php">Riwayat</a></li>
                    <li class="nav-li"><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <a href="keranjang.php" class="icon-link">
                <i class='bx bx-cart-alt'></i>
            </a>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>
        <div id="keranjang">
            <div class="produk-box">
                <div class="produk-container">
                    <div class="col-1">
                        <img src="./img/<?php echo $produk['gambar']; ?>" class="produk-img">
                        <div class="color-box"></div>
                    </div>
                    <div class="col-2">
                        <div class="col-2-box">
                            <h2><?php echo $produk['nama_produk']; ?></h2>
                            <span class="span-bold">Rp<?php echo number_format($produk['harga_produk'], 0, ',', '.'); ?></span>
                            <span>/porsi</span>
                        </div>
                        <h4>Deskripsi :</h4>
                        <div class="desc">
                            <p><?php echo $produk['deskripsi']; ?></p>
                        </div>
                        <p class="tanggal"><?php echo date("d F Y", strtotime($produk['waktu_posting'])); ?></p>
                        <div class="publisher">
                            <div class="publisher-img">
                                <img src="./img/user (3).png" class="user">
                            </div>
                            <h4 class="username"><?php echo $produk['id_toko']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="pembelian">
            <div class="pembelian-box">
                <div class="pembelian-container">
                    <div class="pembelian-content">
                        <h3>Atur jumlah pembelian & note</h3>
                        <div class="keterangan-produk">
                            <div class="ket-img">
                                <img src="./img/<?php echo $produk['gambar']; ?>" alt="">
                            </div>
                            <div class="ket-nama">
                                <p><?php echo $produk['nama_produk']; ?></p>
                            </div>
                        </div>
                        <div class="jumlah-stok">
                            <div class="stok">stok : <?php echo $produk['stok']; ?></div>
                            <div class="jumlah-kolom">
                                <div class="wrapper">
                                    <span class="minus">-</span>
                                    <span class="num">01</span>
                                    <span class="plus">+</span>
                                </div>
                            </div>
                        </div>
                        <div class="harga">
                            <span>harga total :</span><p>Rp<span id="harga-total"><?php echo number_format($produk['harga_produk'], 0, ',', '.'); ?></span></p>
                        </div>
                        <form action="detail.php?ID=<?php echo $produk['ID']; ?>" id="beliForm" method="post">
                            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($produk['ID']); ?>">
                            <input type="hidden" name="jumlah" id="jumlah-input" value="1">
                            <textarea id="noteInput" name="note" placeholder="Tambah catatan"></textarea>
                            <input type="hidden" name="action" value="beli"> <!-- Added hidden input for 'beli' action -->
                            <div class="btn-beli">
                                <button type="submit" id="beliBtn" onclick="beliProduk()">Beli</button>
                            </div>
                            <div class="btn-keranjang">
                                <button type="submit" name="action" value="keranjang" onclick="tambahKeranjang()">Tambah ke keranjang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <p class="copyright">&copy; 2024 Bakoel. Hak Copyright Dilindungi Undang-Undang.</p>
        </div>
    </div>
    
    <script>
        const textarea = document.getElementById('noteInput');

        textarea.addEventListener('input', autoResize);

        function autoResize() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }

        const plus = document.querySelector(".plus"),
            minus = document.querySelector(".minus"),
            num = document.querySelector(".num"),
            hargaTotal = document.getElementById("harga-total"),
            jumlahInput = document.getElementById("jumlah-input");

        let a = 1;
        plus.addEventListener("click", () => {
            if (a < <?php echo $produk['stok']; ?>) {
                a++;
                a = (a < 10) ? "0" + a : a;
                num.innerText = a;
                jumlahInput.value = a;
                hargaTotal.innerText = (a * <?php echo $produk['harga_produk']; ?>).toLocaleString();
            }
        });

        minus.addEventListener("click", () => {
            if (a > 1) {
                a--;
                a = (a < 10) ? "0" + a : a;
                num.innerText = a;
                jumlahInput.value = a;
                hargaTotal.innerText = (a * <?php echo $produk['harga_produk']; ?>).toLocaleString();
            }
        });

        function beliProduk() {
            // Mengambil nilai jumlah dari input
            var jumlah = parseInt(document.getElementById("jumlah-input").value);

            // Memastikan jumlah yang valid
            if (jumlah < 1 || isNaN(jumlah)) {
                alert("Jumlah pembelian tidak valid.");
                return; // Menghentikan proses jika jumlah tidak valid
            }

            document.getElementById("beliForm").submit(); // Mengirim formulir jika jumlah valid
        }

        function tambahKeranjang() {
            document.getElementById("beliBtn").disabled = true; // Menonaktifkan tombol beli
            document.getElementById("beliBtn").innerHTML = "Sedang ditambahkan..."; // Mengubah teks tombol
            setTimeout(function() {
                document.getElementById("beliBtn").innerHTML = "Beli"; // Mengembalikan teks tombol
                document.getElementById("beliBtn").disabled = false; // Mengaktifkan kembali tombol beli
                document.querySelector('form').submit(); // Mengirim formulir setelah beberapa saat
            }, 2000); // 2000 milidetik (2 detik) penundaan sebelum mengirim formulir
        }
    </script>
</body>
</html>
