<?php
//セッション開始
session_start();

//不正遷移の禁止
if (isset($_SESSION["flg2"]) && $_SESSION["flg2"] == "ok") {
    //パスワードの変更に遷移
    if (isset($_POST["pchan"])) {
        $_SESSION["loc"] = 'accounts.php';
        $loc = "pChange.php";
        header("Location: {$loc}");
        exit();
    }

    //メインメニューに遷移
    if (isset($_POST["menu"])) {
        $loc = "./../mains.php";
        header("Location: {$loc}");
        exit();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/accounts.html');
