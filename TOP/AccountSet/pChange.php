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

//値の初期化
$erm = null;
$han = true;
$pass = null;

//反映ボタンが押されたら処理開始
if (isset($_POST["upd"])) {
    //入力欄に入力しているかどうか
    if (!empty($_POST["password0"]) && !empty($_POST["password"]) && !empty($_POST["password2"])) {
        //パスワードの再入力が一致しているかどうか
        if ($_POST["password"] == $_POST["password2"]) {

            //データベースに接続
            $dbh = new PDO($dsn, $username, $passwd);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $stmt = $dbh->prepare('SELECT * FROM account WHERE user_name = :user');
            $stmt->bindParam('user', $_SESSION["user_name"], PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($_POST["password0"], $res["password"])) {
                //パスワードのハッシュ化をセット
                $pass = password_hash(h($_POST["password"]), PASSWORD_DEFAULT);
                //更新のSQL文
                $stmt = $dbh->prepare('UPDATE account SET password = :pset WHERE user_name = :user');
                $stmt->execute(array('pset' => $pass, 'user' => $_SESSION["user_name"]));
                $loc = 'pChangeok.php';
                header("Location: {$loc}");
                exit();
            } else {
                $erm = 'パスワードが一致しません。再度入力してください。';
            }
        } else {
            $erm = 'パスワードが一致しません。再度入力してください。';
        }
    } else {
        $erm = 'パスワードを入力してください。';
    }
}

//キャンセルを押す
if (isset($_POST["canc"])) {
    header("Location: {$_SESSION["loc"]}");
    exit();
}

include('./HTML/pChange.html');
