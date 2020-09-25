<?php
//セッション開始
session_start();

//TOPに戻る
if (isset($_POST["back"])) {
    $loc = 'logout.php';
    header("Location: {$loc}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>成績管理システム　不正遷移</title>
</head>

<body>
    <form action="AccessErr.php" method="POST">
        <p>不正アクセスが検出されました。</p>
        <input type="submit" value="TOPに戻る" name="back">
    </form>
</body>

</html>