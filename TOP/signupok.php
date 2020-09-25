<?php
//セッション開始
session_start();
$name = $_SESSION["user_name"];

if (isset($_POST["signin"])) {
    $loc = 'login.php';
    header("Location: {$loc}");
    exit();
}

if (isset($_POST["top"])) {
    $loc = 'top.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/signupok.html');
