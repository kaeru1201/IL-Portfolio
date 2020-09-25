<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の用意
$msg = null;
$erm = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {
    if (isset($_SESSION["sub_id1"])) {

        $dbh = new PDO($dsn, $username, $passwd);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $dbh->prepare('SELECT * FROM subject WHERE sub_id = :sub_id');
        $stmt->bindParam('sub_id', $_SESSION["sub_id1"], PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $msg1 = $res["sub_id"];
        $msg2 = $res["sub_name"];

        $msg = "科目ID：$msg1 <br /> 科目名: $msg2 <br />";
        $_SESSION["sub_id1"] = null;
    } else {
        $erm = '画面がリロードされた可能性があります。他画面にいきデータの有無を確認してください。';
    }

    //科目画面
    if (isset($_POST["sub"])) {
        $loc = "subjectMenu.php";
        header("Location: {$loc}");
    }

    //成績画面
    if (isset($_POST["gra"])) {
        $loc = "./../Score/scoretMenu.php";
        header("Location: {$loc}");
    }

    //メインメニュー
    if (isset($_POST["menu"])) {
        $loc = "./../maink.php";
        header("Location: {$loc}");
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/subjectS.html');
