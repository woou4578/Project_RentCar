<?php
    session_start();
    if(!session_id()) header('Location: http://localhost/CNU_RENTCAR/login.php');

    $tns = "(DESCRIPTION=
		(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
		(CONNECT_DATA=(SERVICE_NAME=XE)))";
    $url = "oci:dbname=".$tns.";charset=utf8";
    $username = 'CNU_RENTCAR';
    $password = '0000';
    $ID = $_POST['ID'];
    $PWD = $_POST['PWD'];
    try {
        $conn = new PDO($url, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }

    $stmt = $conn -> prepare("SELECT name, email FROM Customer WHERE cno = ? AND passwd = ?");
    $stmt -> execute(array($ID, $PWD));
    while ($row = $stmt -> fetch(PDO::FETCH_NUM)) {
        $_SESSION['name'] =  $row[0];
        $_SESSION['email'] = $row[1];
        $_SESSION['id'] = $ID;
    }

    if(!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
        echo ("<script>
                alert('아이디와 비밀번호를 확인해주세요!');
                location.replace('http://localhost/CNU_RENTCAR/login.php');
            </script>");
        session_destroy();
    } else {
        if($_SESSION['name'] == '관리자') header('Location: http://localhost/CNU_RENTCAR/root_main.php');
        else header('Location: http://localhost/CNU_RENTCAR/user_main.php');
    }
?>