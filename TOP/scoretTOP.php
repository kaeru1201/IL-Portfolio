<?php
//セッション開始
session_start();

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    //教科を登録する
    if (isset($_POST["sub"])) {
        $loc = './subject/subjectMenu.php';
        header("Location: {$loc}");
        exit;
    }

    //成績を登録する
    if (isset($_POST["scoret"])) {
        $loc = './score/scoretMenu.php';
        header("Location: {$loc}");
        exit;
    }

    //メインメニューに戻る
    if (isset($_POST["menu"])) {
        $loc = 'maink.php';
        header("Location: {$loc}");
        exit();
    }
} else {
    $loc = 'AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/scoretTOP.html');
