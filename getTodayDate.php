<?php 
session_start();
if (!isset($_SESSION['name'])) header('Location: login.php');

echo "오늘의 날짜는 ".$_SESSION['todayDate']."입니다.";
?>