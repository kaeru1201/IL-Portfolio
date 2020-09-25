<?php
//セッション開始
session_start();
$_SESSION = array();

//セッションクッキーを削除
if (ini_get("session.use_cookise")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

//セッションクリア
session_destroy();

//ログインページに遷移
$loc = 'top.html';
header("Location: {$loc}");
