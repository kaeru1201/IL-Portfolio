<?php
//セッション開始
session_start();

//不正遷移の禁止
if (isset($_SESSION["flg2"]) && $_SESSION["flg2"] == "ok") {
    $hello = $_SESSION["user_name"];

    //成績表示画面
    if (isset($_POST["scoreDIS"])) {
        $loc = "./Score/scoreDIS.php";
        header("Location: {$loc}");
        exit();
    }

    //アカウントの設定
    if (isset($_POST["account"])) {
        $loc = "./AccountSet/accounts.php";
        header("Location: {$loc}");
        exit();
    }

    //ログアウトの処理を追加する
    if (isset($_POST["logout"])) {
        $loc = "logout.php";
        header("Location: {$loc}");
    }
} else {
    $loc = 'AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/mains.html');
