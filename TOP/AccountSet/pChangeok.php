<?php
//変数の初期化
$msg = null;

$msg = 'パスワードが変更されました。再ログインしてください。';

if (isset($_POST["login"])) {
    $loc = './../logout.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/pChangeok.html');
