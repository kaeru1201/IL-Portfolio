<?php
//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//値の初期化
$erm = null;

//セッションを開始
session_start();

//XSS対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//登録ボタンが押されてから処理開始
if (isset($_POST["login"])) {
    //入力フォームが空ではないかどうかを判定
    if (!empty($_POST["user_name"]) && !empty($_POST["password"])) {

        //データベースに接続
        $dbh = new PDO($dsn, $username, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        /*データベースに接続できたかを確認
        if ($link != null) {
            echo "データベースに接続しました。<br />";
        }
        */

        try {
            //SQL文
            $stmt = $dbh->prepare('SELECT * FROM account WHERE user_name = ?');
            $stmt->execute(array(h($_POST["user_name"])));

            if ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //教師用
                if ($res["user_id"] == 1) {
                    if (password_verify($_POST["password"], $res["password"])) {
                        session_regenerate_id(true);
                        $_SESSION["user_name"] = $res["user_name"];
                        $_SESSION["flg1"] = "ok";
                        $loc = "maink.php";
                        header("Location: {$loc}");
                        exit;
                    }

                    //生徒用
                } elseif (password_verify($_POST["password"], $res["password"])) {
                    session_regenerate_id(true);
                    $_SESSION["user_name"] = $res["user_name"];
                    $_SESSION["flg2"] = "ok";
                    $loc = "mains.php";
                    header("Location: {$loc}");
                    exit;
                }
            }
            //上記の判定のどちらもfalseだった場合エラー表記
            $erm = "ユーザ名、またはパスワードが違います。";

            //例外処理
        } catch (PDOException $e) {
            echo 'データベースに接続できませんでした。', $e->getMessage();
            die();
        }
    } else {
        //空だった場合エラーを表示
        $erm = "ユーザ名、またはパスワードを入力してください";
    }
}
if (isset($_POST["signup"])) {
    $loc = 'signup.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/login.html');
