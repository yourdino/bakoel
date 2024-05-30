<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="stylefix.css">
</head>
<body>
    
    <div class="container">
        <div class="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="./login.php">Home</a></li>
                    <li><a href="./login.php">Belanja</a></li>
                    <li><a href="./login.php">Riwayat</a></li>
                    <li><a href="./login.php">Login</a></li>
                </ul>
            </nav>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>
        <div class="main">
            <div class="row">
                <div class="col-1">
                    <h2>Selamat Datang di BAKOEL!</h2>
                    <h3>Lorem ipsum dolor sit amet, <br>consectetur adipisicing elit. <br> Magnam officia modi, iusto,<br> minima sed alias,</h3>
                    <button type="button" class="btn-tagline"><a href="./login.php">Buat Akun</a></button>
                </div>
                <div class="col-2">
                    <img src="./img/donut.png" class="donut">
                    <div class="color-box"></div>
                </div>
            </div>
            <h1 class="fitur-tagline">Awesome Features</h1>
            <div class="cover-atas"></div>
        </div>
        <div class="fitur"></div>
        <div id="fitur1">
            <div class="fitur1-col-1">
                <img src="./img/cashless-payment.png" class="cashless-payment">
            </div>
            <div class="fitur1-col-2">
                <h2>Cashless Payment</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam magni, vero inventore doloribus blanditiis ipsam ullam beatae voluptatum</p>
            </div>
        </div>
        <div id="fitur2">
            <div class="fitur2-col-1">
                <img src="./img/cash-on-delivery (1).png" class="cash-on-delivery">
            </div>
            <div class="fitur2-col-2">
                <h2>Cash on Delivery</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam magni, vero inventore doloribus blanditiis ipsam ullam beatae voluptatum</p>
            </div>
        </div>
        <div id="fitur3">
            <div class="fitur3-col-1">
                <img src="./img/contact-mail.png" class="contact-mail">
            </div>
            <div class="fitur3-col-2">
                <h2>Contact Mail</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam magni, vero inventore doloribus blanditiis ipsam ullam beatae voluptatum</p>
            </div>
        </div>

        <div id="jualan">
            <div class="spacer curve-atas"></div>
            <div class="konten-jual">
                <div class="jualan-col-1">
                    <img src="./img/filedonut.png" alt="">
                </div>
                <div class="jualan-col-2">
                    <h2>Mulai Jual Beli</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi quo odio quia iusto debitis sint aliquid reiciendis nihil voluptatum quaerat? Blanditiis ut neque optio fugiat adipisci vel corporis nam vitae?</p>
                    <button class="btn-JB"><a href="./login.php">Buat Akun</a></button>
                </div>
            </div>
            <div class="spacer curve-bawah"></div>
        </div>
        <footer>
            <h1 class="footer-tagline">Contact Us on :</h1>
            <div class="social-links">
                <img src="./bxl-whatsapp.svg" alt="">
                <img src="./bxl-instagram.svg" alt="">
                <img src="./bxl-github.svg" alt="">
            </div>
            <p>&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
        </footer>
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