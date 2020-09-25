<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

if (isset($_SESSION["flg2"]) && $_SESSION["flg2"] == "ok") {

    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {

        $stmt = $dbh->prepare('SELECT user_name,subject.sub_name,rating,credit,attitude,absence FROM score,subject WHERE score.sub_id = subject.sub_id AND user_name = :usern ORDER BY user_name');
        $stmt->bindParam("usern", $_SESSION['user_name'], PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_POST["menu"])) {
            $loc = './../mains.php';
            header("Location: {$loc}");
            exit();
        }
    } catch (PDOException $e) {
        echo 'データベースに接続できません。', $e->getMessage();
        die();
    }
} else {
    $loc = 'AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/scoreDIS.html');
