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
$not = true;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    //登録ボタンが押されてから処理開始
    if (isset($_POST["regi"])) {
        //入力フォームが空ではないかどうか判定
        if (!empty($_POST["sub_name"])) {

            //データベースに接続
            $dbh = new PDO($dsn, $username, $passwd);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            try {
                //入力されたデータをテーブルに保存、同時に登録完了ページに移動。
                $stmt = $dbh->prepare('INSERT INTO subject (sub_name) VALUES (:sub_name)');
                $stmt->execute(array('sub_name' => h($_POST["sub_name"])));
                $sub_id = $dbh->lastInsertId();
                $_SESSION["sub_id1"] = $sub_id;
                $loc = "subjectS.php";
                header("Location: {$loc}");
                exit();

                //例外処理
            } catch (PDOException $e) {
                $erm = 'この教科名は既に登録されています。';
            }
        } else {
            //空だった場合エラーを表示
            $erm = '教科名を入力してください。';
        }
    }
    if (isset($_POST["canc"])) {
        $loc = 'subjectMenu.php';
        header("Location: {$loc}");
        exit();
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/subject.html');
