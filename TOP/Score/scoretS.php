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
    if (isset($_SESSION["score_id"])) {

        $dbh = new PDO($dsn, $username, $passwd);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $dbh->prepare('SELECT score_id, user_name, subject.sub_name, rating, credit, attitude, absence FROM score,subject WHERE score_id = :score_id AND score.sub_id = subject.sub_id');
        $stmt->bindParam('score_id', $_SESSION["score_id"], PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $msg1 = $res["score_id"];
        $msg2 = $res["user_name"];
        $msg3 = $res["sub_name"];
        $msg4 = $res["rating"];
        $msg5 = $res["credit"];
        $msg6 = $res["attitude"];
        $msg7 = $res["absence"];

        $msg = "成績ID: $msg1 <br /> 登録者名: $msg2 <br /> 教科ID: $msg3 <br /> 評定: $msg4 <br /> 単位: $msg5 <br /> 授業態度: $msg6 <br /> 欠席日数: $msg7 <br />";
        $_SESSION["score_id"] = null;
    } else {
        $erm = '画面がリロードされた可能性があります。他画面にいきデータの有無を確認してください。';
    }
    //科目画面
    if (isset($_POST["sub"])) {
        $loc = "./../Subject/subjectMenu.php";
        header("Location: {$loc}");
        exit();
    }

    //成績画面
    if (isset($_POST["gra"])) {
        $loc = "scoretMenu.php";
        header("Location: {$loc}");
        exit();
    }

    //メインメニュー
    if (isset($_POST["menu"])) {
        $loc = "./../maink.php";
        header("Location: {$loc}");
        exit();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$Loc}");
    exit();
}

include('./HTML/scoretS.html');
