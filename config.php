<?php

$username = "pembayun";
$password = "12345";
$host =  "localhost";

$is_connect = mysqli_connect($host, $username, $password); // mysql -u phpmyadmin -p -h localhost

if($is_connect){
    mysqli_select_db($is_connect, "bakoeel"); // use izin_db di mysql
}else{
    echo "Try Again...";
}