<?php
//セッション開始
session_start();

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {
    //パスワードの変更に遷移
    if (isset($_POST["pchan"])) {
        $_SESSION["loc"] = 'accountk.php';
        $loc = "pChange.php";
        header("Location: {$loc}");
        exit();
    }

    //アカウントの削除に遷移
    if (isset($_POST["dele"])) {
        $loc = "acDele.php";
        header("Location: {$loc}");
        exit();
    }

    //メインメニューに遷移
    if (isset($_POST["menu"])) {
        $loc = "./../maink.php";
        header("Location: {$loc}");
        exit();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/accountk.html');
