<?php
//セッション開始
session_start();

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    //成績表示画面
    if (isset($_POST["scoret1"])) {
        $loc = "scoretTOP.php";
        header("Location: {$loc}");
        exit;
    }

    //アカウントの設定
    if (isset($_POST["account"])) {
        $loc = "./AccountSet/accountk.php";
        header("Location: {$loc}");
        exit;
    }

    //ログアウトの処理を追加する
    if (isset($_POST["logout"])) {
        $_SESSION = array();
        $loc = "logout.php";
        header("Location: {$loc}");
    }
} else {
    $loc = 'AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/maink.html');
