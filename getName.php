<?php
session_start();
if (!isset($_SESSION['name'])) header('Location: login.php');
echo $_SESSION['name'];
?>