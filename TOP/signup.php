<?php
//XSS対策
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の初期化
$erm = null;
$han = true;

//登録ボタンが押されたら処理開始
if (isset($_POST["signup"])) {
    //入力フォームが空ではないかどうかを判定
    if (!empty($_POST["user_name"]) && !empty($_POST["password"]) && !empty($_POST["password2"])) {

        //データベースに接続
        $dbh = new PDO($dsn, $username, $passwd);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        try {
            //パスワードが再入力と一致しているか確認
            if ($_POST["password"] === $_POST["password2"]) {
                $stmt = $dbh->prepare('INSERT INTO account (user_id,user_name, password) VALUES (?,?, ?)');
                $stmt->execute(array(0, h($_POST["user_name"]), password_hash(h($_POST["password"]), PASSWORD_DEFAULT)));
                $_SESSION["user_name"] = $_POST["user_name"];
                $loc = "signupok.php";
                header("Location: {$loc}");
                exit;
            } else {
                $erm = 'パスワードが一致していません。再度入力してください。';
            }
            //例外処理
        } catch (PDOException $e) {
            $erm = 'このユーザ名は既に登録されています。';
        }
    } else {
        //空だった場合エラーを表示
        $erm = "ユーザー名、パスワードを入力してください。";
    }
}

if (isset($_POST["login"])) {
    $loc = 'login.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/signup.html');
