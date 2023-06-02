<?php
session_start();
if (isset($_SESSION['id'])) {
    $rentcar_login = TRUE;
}
?>