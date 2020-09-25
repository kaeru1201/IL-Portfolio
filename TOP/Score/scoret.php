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

//変数の用意
$user_list = null;
$sub_list = null;
$erm = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    //データベースに接続
    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    try {
        //生徒ID用
        $stmt = $dbh->prepare('SELECT * FROM account WHERE user_id = :user_id');
        $stmt->execute(array("user_id" => 0));
        $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($user_data as $user_data_val) {
            $user_list .= "<option>" . $user_data_val['user_name'] . "</option>";
        }

        //教科ID用
        $stmt = $dbh->prepare('SELECT * FROM subject');
        $stmt->execute();
        $sub_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sub_data as $sub_data_val) {
            $sub_list .= "<option value='" . $sub_data_val['sub_id'];
            $sub_list .= "'>" . $sub_data_val['sub_name'] . "</option>";
        }

        //登録が押された場合
        if (isset($_POST["regi"])) {
            if (!empty($_POST["credit"] && isset($_POST["absence"]))) {
                $stmt = $dbh->prepare('INSERT INTO score(user_name, sub_id, rating, credit, attitude, absence) VALUES (:user_name, :sub_id, :rating, :credit, :attitude, :absence)');
                $stmt->execute(array(':user_name' => $_POST["user_dn"], ':sub_id' => $_POST["sub_dn"], ':rating' => $_POST["rating"], ':credit' => h($_POST["credit"]), ':attitude' => $_POST["attitude"], ':absence' => h($_POST["absence"])));
                $score_id = $dbh->lastInsertId();
                $_SESSION["score_id"] = $score_id;
                $loc = "scoretS.php";
                header("Location: {$loc}");
                exit;
            } else {
                $erm = "単位、欠席日数が入力されていません。";
            }
        }
    } catch (PDOException $e) {
        $erm = '入力方法に誤りがあります。確認の上、再度入力してください。';
    }
} else {
    $loc = './../AccessErr.php';
    header("Location: {$loc}");
    exit();
}

//メインメニューに戻るが押された場合
if (isset($_POST["menu"])) {
    $menu = "./scoretMenu.php";
    header("Location: {$menu}");
    exit();
}

include('./HTML/scoret.html');
