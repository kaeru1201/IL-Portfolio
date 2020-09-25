<?php
//セッション開始
session_start();

//XSS対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の初期化
$erm = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {
        //更新
        if (isset($_POST["upd"])) {
            if (!empty($_POST["sn"])) {
                $stmt = $dbh->prepare('UPDATE subject SET sub_name = :asub WHERE sub_name = :bsub');
                $stmt->execute(array("asub" => h($_POST["sn"]), "bsub" => $_SESSION["bsub"]));
                $_SESSION["asub"] = $_POST["sn"];
                $_SESSION["flg_ss"] = "ok";
                $loc = 'subjectE.php';
                header("Location: {$loc}");
                exit();
            } else {
                $erm = '教科名を入力してください。';
            }
        }
    } catch (PDOException $e) {
        $erm = 'この教科名は既に登録されています。';
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

//キャンセル
if (isset($_POST["canc"])) {
    $_SESSION["bsub"] = null;
    $loc = 'subjectE.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/subjectU.html');
