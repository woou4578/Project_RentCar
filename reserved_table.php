<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>

<?php 
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');
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

$ID = $_SESSION['id'];
echo "<tr><th>선택</th><th>차번호</th>
    <th>예약날짜</th><th>렌트시작날짜</th>
    <th>렌트종료날짜</th><th>ID</th></tr>";

$sql = "SELECT * FROM RESERVATION WHERE CNO = '" . $ID . "'";
$stmt = $conn -> prepare($sql);
$stmt -> execute();
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo "<tr> <td> <input type='radio' name='cancelRadio'> </td>";
    echo "<td>".$row[0]."</td>";
    echo "<td>".$row[1]."</td>";
    echo "<td>".$row[2]."</td>";
    echo "<td>".$row[3]."</td>";
    echo "<td>".$row[4]."</td>";
    echo "</tr>";
}
?>