<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の用意
$msg = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {
    //データベースに接続
    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {
        //SQL文
        $stmt = $dbh->prepare('SELECT * FROM account WHERE user_id = :user_id');
        $stmt->execute(array("user_id" => 0));
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'データベースに接続できません。', $e->getMessage();
    }
} else {
    $loc = 'AccessErr.php';
    header("Location: {$loc}");
    exit();
}

//削除されたアカウントがあった場合に表示
if (isset($_SESSION["user_dele"])) {
    $msg = 'アカウント名：' . $_SESSION["user_dele"] . 'が削除されました。';
    $_SESSION["user_dele"] = null;
}

//削除する
if (isset($_POST["dele"])) {
    if (isset($_POST["cs"])) {
        $_SESSION["user_dele"] = $_POST["cs"];
        $loc = 'acDeleC.php';
        header("Location: {$loc}");
        exit();
    } else {
        $msg = 'データが選択されていません。';
    }
}

//キャンセル
if (isset($_POST["canc"])) {
    $loc = 'accountk.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/acDele.html');
