<?php
session_start(); 
if (isset($_SESSION['ID'])) { //isset > data ada
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login-style.css"> 
    <title>Bakoel</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action= "proses_signup.php" method="post">
                <h1>Buat Akun</h1>
                <span>gunakan email untuk registrasi</span>
                <!--<form action= "proses_login.php" method="post">-->
                <input type="text" name="username" placeholder="Username">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button>Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action= "proses_login.php" method="post">
                <h1>Sign In</h1>
                <span>gunakan email dan password</span>
                <input type="email" name='email' placeholder="Email">
                <input type="password" name='password' placeholder="Password">
                <!-- <a href="#">Forget Your Password?</a> -->
                <button type="submit">Sign in</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Jika sudah mendaftar, masuk menggunakan email dan password saja untuk mengunakan fitur yang tersedia sebebasnya</p>
                    <a href="./home.php">Sign In</a>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Baru di Bakoel? Daftar mengenakan sosial media ataupun email, klik tombol dibawah</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="login-script.js"></script>
</body>

</html>