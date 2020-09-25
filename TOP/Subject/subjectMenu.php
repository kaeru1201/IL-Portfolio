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
        //SQL文
        $stmt = $dbh->prepare('SELECT * FROM subject ORDER BY sub_id');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //登録ボタンが押されたら登録画面に遷移
        if (isset($_POST["regi"])) {
            $loc = 'subject.php';
            header("Location: {$loc}");
            exit();
        }

        //編集画面が押されたら編集画面に遷移
        if (isset($_POST["edit"])) {
            $loc = 'subjectE.php';
            header("Location: {$loc}");
            exit();
        }

        //メインメニューに戻る
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

include('./HTML/subjectMenu.html');
