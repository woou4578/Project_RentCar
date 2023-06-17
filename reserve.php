<?php
$startDate = $_GET['firstDate'] ?? '';
$endDate = $_GET['secondDate'] ?? '';
$carNumber = $_GET['carNumber'] ?? '';

// 로그인 확인
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// DB 연결
$tns = "
    (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'CNU_RENTCAR';
$password = '0000';
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

// 예약을 진행하는 부분으로
// 전달받은 값들(차번호, 예약 날짜, 렌트시작날짜, 렌트종료날짜, 예약한 id)로 예약을 추가한다.
$today = date('m/d/y', strtotime($_SESSION['todayDate']));
$newStartDate = date('m/d/y', strtotime($startDate));
$newEndDate = date('m/d/y', strtotime($endDate));

$sql2 = "INSERT INTO RESERVATION VALUES ('" . $carNumber . "',
            TO_DATE( '" . $today . "' , 'MM/DD/YY'),
            TO_DATE( '" . $newStartDate . "' , 'MM/DD/YY'),
            TO_DATE( '" . $newEndDate . "' , 'MM/DD/YY'),
            '" . $_SESSION['id'] . "')";
$stmt = $conn->prepare($sql2);
$stmt->execute();

echo "예약하였습니다.";
?>