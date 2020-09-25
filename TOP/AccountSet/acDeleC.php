<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=IL; host=localhost; charset=utf8';
$username = 'kaeru1201';
$passwd = 'hirono2173';

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {
    //データベースに接続
    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {
        //削除
        if (isset($_POST["dele"])) {
            $stmt1 = $dbh->prepare('DELETE FROM account WHERE user_name = :user_name');
            $stmt1->execute(array("user_name" => $_SESSION["user_dele"]));
            $stmt2 = $dbh->prepare(('DELETE FROM score WHERE user_name = :user_name'));
            $stmt2->execute(array("user_name" => $_SESSION["user_dele"]));
            $loc = 'acDele.php';
            header("Location: {$loc}");
            exit();
        }
    } catch (PDOException $e) {
        echo 'データベースに接続できません。', $e->getMessage();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

//キャンセル
if (isset($_POST["canc"])) {
    $_SESSION["user_dele"] = null;
    $loc = 'acDele.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/acDeleC.html');
