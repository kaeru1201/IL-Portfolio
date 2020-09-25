<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


    try {

        $stmt = $dbh->prepare('SELECT score_id,user_name,subject.sub_name,rating,credit,attitude,absence FROM score,subject WHERE score.sub_id = subject.sub_id ORDER BY user_name');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_POST["regi"])) {
            $loc = 'scoret.php';
            header("Location: {$loc}");
            exit();
        }

        if (isset($_POST["edit"])) {
            $loc = 'scoretE.php';
            header("Location: {$loc}");
            exit();
        }

        if (isset($_POST["back"])) {
            $loc = './../scoretTOP.php';
            header("Location: {$loc}");
            exit();
        }
    } catch (PDOException $e) {
        echo 'データベースに接続できません。', $e->getMessage();
        die();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/scoretMenu.html');
