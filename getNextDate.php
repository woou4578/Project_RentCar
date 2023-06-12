<?php 
session_start();
if (!isset($_SESSION['name'])) header('Location: login.php');
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

// 바뀌는 날짜 설정
$_SESSION['todayDate'] = '2023-06-22';
$today = $_SESSION['todayDate'];
$checkValue = "";

$selectedArr = Array();

$sql = "SELECT LICENSEPLATENO, STARTDATE, ENDDATE, CNO FROM RESERVATION WHERE STARTDATE = '".$today."'";
$stmt = $conn -> prepare($sql);
$stmt -> execute();

while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    array_push($selectedArr, Array($row[0], $row[1], $row[2], $row[3]));
}

for($i = 0; $i < count($selectedArr); $i++) {
    $sql2 = "UPDATE RENTCAR SET DATERENTED = '".$selectedArr[$i][1]."',
            RETURNDATE = '".$selectedArr[$i][2]."',
            CNO = '".$selectedArr[$i][3]."'
        WHERE LICENSEPLATENO = '".$selectedArr[$i][0]."'";
    $stmt = $conn -> prepare($sql2);
    $stmt -> execute();
}
for($i = 0; $i < count($selectedArr); $i++) {
    $sql3 = "DELETE FROM RESERVATION 
        WHERE CNO = '".$selectedArr[$i][3]."' AND
        STARTDATE = TO_DATE('".$selectedArr[$i][1]."', 'YY/MM/DD')";
    $stmt = $conn -> prepare($sql3);
    $stmt -> execute();
}

echo "오늘의 날짜는 ".$_SESSION['todayDate']."입니다.";

?>