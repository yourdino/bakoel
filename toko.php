<?php
include 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['ID'];

// Jika form pengajuan toko disubmit
if (isset($_POST['ajukan'])) {
    // Ambil data dari form
    $nama_toko = $_POST['nama_toko'];
    $nomor_telepon = $_POST['nomor_telepon'];

    // Query untuk menyimpan data ke tabel toko
    $insert_toko_query = "INSERT INTO toko (id_user, nama_toko, nomor_telepon) VALUES ('$id_user', '$nama_toko', '$nomor_telepon')";

    // Eksekusi query
    if ($is_connect->query($insert_toko_query) === TRUE) {
        // Jika data berhasil disimpan, redirect ke halaman profile.php
        header("Location: profile.php");
        exit;
    } else {
        // Jika terjadi kesalahan dalam penyimpanan data, tampilkan pesan error
        echo "Error: " . $insert_toko_query . "<br>" . $is_connect->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Ajukan Toko</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="./toko.css">
</head>
<body>
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="belanja.php">Belanja</a></li>
                    <li><a href="history.php">Riwayat</a></li>
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
                    <?php
                    // Periksa apakah user sudah memiliki toko
                    $check_toko_query = "SELECT * FROM toko WHERE id_user='$id_user'";
                    $check_toko_result = $is_connect->query($check_toko_query);

                    if ($check_toko_result->num_rows > 0) {
                        // Jika user sudah memiliki toko, tampilkan detail toko
                        $toko = $check_toko_result->fetch_assoc();
                        echo "<h1 class='judul'>Detail Toko</h1>";
                        echo "<p>Nama Toko: " . $toko['nama_toko'] . "</p>";
                        echo "<p>Nomor Telepon: " . $toko['nomor_telepon'] . "</p>";
                    } else {
                        // Jika user belum memiliki toko, tampilkan form untuk mengajukan toko
                        echo "<h1 class='judul'>Ajukan Toko</h1>";
                        echo "<p>Masukkan Informasi yang diperlukan untuk mendaftarkan toko</p>";
                        echo "<div class='form-label'>";
                        echo "<form action='toko.php' method='POST'>"; // Ganti action ke toko.php
                        echo "<label for='nomor_telepon'>Nomor Telepon</label>";
                        echo "<input type='tel' name='nomor_telepon' id='nomor_telepon' class='input-box'>";
                        echo "<label for='nama_toko'>Nama Toko</label>";
                        echo "<input type='text' name='nama_toko' id='nama_toko' class='input-box'>";
                        echo "<input type='submit' name='ajukan' value='Ajukan'>";
                        echo "</form>";
                        echo "</div>";
                    }
                    ?>
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

            if(menuList.style.maxHeight == "0px")
                {
                    menuList.style.maxHeight = "130px";
                }
            else
                {
                    menuList.style.maxHeight = "0px";
                }
            
        }
    </script>
    
</body>
</html>
