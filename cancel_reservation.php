<?php
$startDate = $_GET['startDate'] ?? '';

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

// 저장된 id와 ajax를 통해 입력받은 stardate를 통해 DB에 저장된 데이터를 삭제한다.
$ID = $_SESSION['id'];
$sql2 = "DELETE FROM RESERVATION WHERE CNO = :ID AND STARTDATE = TO_DATE(:startDate, 'YY/MM/DD')";
$stmt = $conn->prepare($sql2);
$stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
$stmt->execute();

echo "예약 취소되었습니다.";
?>