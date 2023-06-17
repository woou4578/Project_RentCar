<?php
// 로그인 확인
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// 오늘의 날짜를 알려주는 부분
echo "오늘의 날짜는 " . $_SESSION['todayDate'] . "입니다.";
?>