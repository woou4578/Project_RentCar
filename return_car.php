<?php
// 로그인 확인 
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// mailer.php에 있는 함수 이용
include("./mailer.php");

// DB 연결 설정
$tns = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'CNU_RENTCAR';
$password = '0000';
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

$carNumber = $_GET['carNumber'];
$rentDate = $_GET['rentDate'];
$returnDate = $_GET['returnDate'];
$payment = $_GET['payment'];
$cno = $_SESSION['id'];
$today = date('m/d/y', strtotime($_SESSION['todayDate']));

// PreviousRental 테이블에 대여 했던 정보 추가
$sql = "INSERT INTO PREVIOUSRENTAL VALUES ('" . $carNumber . "', TO_DATE( '" . $rentDate . "', 'YY/MM/DD'), TO_DATE( '" . $today . "', 'MM/DD/YY')," . $payment . ", '" . $cno . "')";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Rentcar 테이블에 대여 했던 정보 반납으로 갱신
$sql2 = "UPDATE RENTCAR SET DATERENTED = NULL, RETURNDATE = NULL, CNO = NULL WHERE LICENSEPLATENO = '" . $carNumber . "'";
$stmt = $conn->prepare($sql2);
$stmt->execute();

// 반납 완료 이메일 발송
mailer("cnu.rentcar10@gmail.com", "반납 완료 안내 메일 - CNU_RentCar", $carNumber . "차량에 대한 반납이 완료되었습니다.<br> 청구 금액은 " . $payment . "원 입니다.");
?>