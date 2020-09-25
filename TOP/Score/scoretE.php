<?php
//セッション開始
session_start();

//値の設定
$dsn = 'mysql:dbname=hoge; host=localhost; charset=utf8;';
$username = 'hoge';
$pass = 'hoge';

//変数の初期化
$msg = null;
$msg2 = null;
$erm = null;

//不正遷移の禁止
if (isset($_SESSION["flg1"]) && $_SESSION["flg1"] == "ok") {

    $dbh = new PDO($dsn, $username, $passwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    try {
        //SQL文
        $stmt = $dbh->prepare('SELECT score_id,user_name,subject.sub_name,rating,credit,attitude,absence FROM score,subject WHERE score.sub_id = subject.sub_id ORDER BY user_name');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //更新された項目がある場合、表示する
        if (isset($_SESSION["score_id"]) && isset($_SESSION["flg_ss"])) {
            $stmt1 = $dbh->prepare('SELECT score_id, user_name, subject.sub_name, rating, credit, attitude, absence FROM score,subject WHERE score_id = :score_id AND score.sub_id = subject.sub_id');
            $stmt1->bindParam('score_id', $_SESSION["score_id"], PDO::PARAM_INT);
            $stmt1->execute();
            $res1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $msg = '成績ID：' . $_SESSION["score_id"] . '番が更新されました。';
            $msg1 = $res1["score_id"];
            $msg2 = $res1["user_name"];
            $msg3 = $res1["sub_name"];
            $msg4 = $res1["rating"];
            $msg5 = $res1["credit"];
            $msg6 = $res1["attitude"];
            $msg7 = $res1["absence"];
            $msg2 =  "<br /> 成績ID: $msg1 <br /> 登録者名: $msg2 <br /> 教科ID: $msg3 <br /> 評定: $msg4 <br /> 単位: $msg5 <br /> 授業態度: $msg6 <br /> 欠席日数: $msg7 <br />";
            $_SESSION["flg_ss"] = null;
            $_SESSION["score_id"] = null;
        }

        //削除された項目がある場合、表示する
        if (isset($_SESSION["score_dele"])) {
            $msg = '成績ID：' . $_SESSION["score_dele"] . '　が削除されました。';
            $_SESSION["score_dele"] = null;
        }

        //削除
        if (isset($_POST["dele"])) {
            if (isset($_POST["cs"])) {
                $stmt1 = $dbh->prepare('DELETE FROM score WHERE score_id = :score_id');
                $stmt1->execute(array(":score_id" => $_POST["cs"]));
                $_SESSION["score_dele"] = $_POST["cs"];
                $loc = 'scoretE.php';
                header("Location: {$loc}");
                exit();
            } else {
                $erm = 'データが選択されていません。';
            }
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

//編集
if (isset($_POST["edit"])) {
    if (isset($_POST["cs"])) {
        $_SESSION["score_id"] = $_POST["cs"];
        $loc = 'scoretU.php';
        header("Location: {$loc}");
        exit();
    } else {
        $erm = 'データが選択されていません。';
    }
}

//キャンセル
if (isset($_POST["canc"])) {
    $loc = 'scoretMenu.php';
    header("Location: {$loc}");
    exit();
}

include('./HTML/scoretE.html');
