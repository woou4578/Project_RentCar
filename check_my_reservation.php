<?php
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

echo "<tr><th>선택</th><th>차번호</th>
    <th>모델명</th><th>예약날짜</th>
    <th>렌트시작날짜</th><th>렌트종료날짜</th></tr>";

// 나의 예약 정보를 session에 저장된 id로 구분해 DB에서 가져오는 부분
$sql = "SELECT R1.LICENSEPLATENO, R2.MODELNAME , R1.RESERVEDATE, R1.STARTDATE, R1.ENDDATE
FROM RESERVATION R1, RENTCAR R2
WHERE R1.LICENSEPLATENO = R2.LICENSEPLATENO
AND R1.CNO = '" . $_SESSION['id'] . "'
ORDER BY 3, 2";
$stmt = $conn->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo "<tr> <td> <input type='radio' name='cancelRadio'> </td>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . $row[4] . "</td>";
    echo "</tr>";
}
?>