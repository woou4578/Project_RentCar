<?php 
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>

<?php
$startDate = $_GET['firstDate'] ?? '';
$endDate = $_GET['secondDate'] ?? '';
$vehicleType = $_GET['vType'] ?? '';

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
    
echo "<tr><th>선택</th><th>차번호</th>
    <th>모델명</th><th>차종</th></tr>";

$selectSpecificType1 = "AND C1.VEHICLETYPE = '" . $vehicleType . "'";
$selectSpecificType2 = "AND C2.VEHICLETYPE = '" . $vehicleType . "'";

if ($vehicleType == "전체") {
    $selectSpecificType1 = "";
    $selectSpecificType2 = "";
}

$sql = "SELECT R1.LICENSEPLATENO, R1.MODELNAME, C1.VEHICLETYPE
    FROM RENTCAR R1, CARMODEL C1
    WHERE NOT EXISTS (
        SELECT 1
        FROM RESERVATION RV
        WHERE (NOT (RV.ENDDATE < TO_DATE('".$startDate."', 'YYYY-MM-DD') 
            OR RV.STARTDATE > TO_DATE('".$endDate."', 'YYYY-MM-DD'))) 
        AND R1.LICENSEPLATENO = RV.LICENSEPLATENO) 
        AND R1.MODELNAME = C1.MODELNAME ".$selectSpecificType1."
    INTERSECT 
    SELECT R2.LICENSEPLATENO, R2.MODELNAME, C2.VEHICLETYPE
    FROM RENTCAR R2, CARMODEL C2
    WHERE R2.DATERENTED IS NULL
        OR (R2.RETURNDATE < TO_DATE('".$startDate."', 'YYYY-MM-DD') 
        OR R2.DATERENTED > TO_DATE('".$endDate."', 'YYYY-MM-DD'))
        AND R2.MODELNAME = C2.MODELNAME ".$selectSpecificType2;
$stmt = $conn -> prepare($sql);
$stmt -> execute();
while ($row = $stmt -> fetch(PDO::FETCH_NUM)) {
    echo "<tr> <td> <input type='radio' name='reserveRadio'> </td>";
    echo "<td>".$row[0]."</td>";
    echo "<td>".$row[1]."</td>";
    echo "<td>".$row[2]."</td>";
    echo "</tr>";
}
    
?>
