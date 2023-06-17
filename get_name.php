<?php
// 로그인 확인 부분
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// 로그인 한 이름을 불러온다.
echo $_SESSION['name'];
?>