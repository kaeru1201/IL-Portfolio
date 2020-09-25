<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の初期化
$msg = null;
$erm = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {
        //SQL文
        $stmt = $dbh->prepare('SELECT * FROM subject ORDER BY sub_id');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'データベースに接続できません。', $e->getMessage();
        die();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

//削除された項目がある場合、表示する
if (isset($_SESSION["sub_dele"])) {
    $msg = '<p>' . $_SESSION["sub_dele"] . 'を削除しました。</p>';
    $_SESSION["sub_dele"] = null;
}

//更新された項目がある場合、表示する
if (isset($_SESSION["bsub"]) && isset($_SESSION["flg_ss"])) {
    $msg =  '<p>' . $_SESSION["bsub"] . 'が' . $_SESSION["asub"] . 'に変更されました。';
    $_SESSION["bsub"] = null;
    $_SESSION["asub"] = null;
    $_SESSION["flg_ss"] = null;
} else {
    $_SESSION["bsub"] = null;
}

//編集が押されたとき
if (isset($_POST["edit"])) {
    if (isset($_POST["cs"])) {
        $_SESSION["bsub"] = $_POST["cs"];
        $loc = 'subjectU.php';
        header("Location: {$loc}");
        exit();
    } else {
        $erm = 'データが選択されていません。';
    }
}

//削除が押されたとき
if (isset($_POST["dele"])) {
    if (isset($_POST["cs"])) {
        $stmt = $dbh->prepare('DELETE FROM subject WHERE sub_name = :sub_name');
        $stmt->execute(array(':sub_name' => $_POST["cs"]));
        $_SESSION["sub_dele"] = $_POST["cs"];
        $loc = 'subjectE.php';
        header("Location:{$loc}");
    } else {
        $erm = 'データが選択されていません。';
    }
}

//戻るを押されたとき
if (isset($_POST["back"])) {
    $loc = 'subjectMenu.php';
    header("Location: {$loc}");
    exit();
}
include('./HTML/subjectE.html');
