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


$checkSQL = "SELECT
CASE
    WHEN (R.STARTDATE NOT BETWEEN
        TO_DATE('".$startDate."', 'YY/MM/DD') AND 
        TO_DATE('".$endDate."', 'YY/MM/DD'))
        AND( R.ENDDATE NOT BETWEEN 
        TO_DATE('".$startDate."', 'YY/MM/DD') AND
        TO_DATE('".$endDate."', 'YY/MM/DD'))
    THEN '가능'
    ELSE '불가능'
END AS bb 
FROM RESERVATION R
WHERE CNO = '".$_SESSION['id']."'";
$stmt = $conn -> prepare($checkSQL);
$stmt -> execute();

$valval = "";
while ($row = $stmt -> fetch(PDO::FETCH_NUM)) {
    if($row[0] == "불가능") {
        $valval = "불가능";
        break;
    }
}

if ($valval == "불가능") {
  echo "예약 가능한 차가 없습니다.";  
} else {
    echo "<tr><th>선택</th><th>차번호</th>
    <th>모델명</th><th>차종</th><th>옵션</th></tr>";

    $selectSpecificType1;
    $selectSpecificType2;
    if ($vehicleType[0] == "전체") {
        $selectSpecificType1 = "";
        $selectSpecificType2 = "";
    } else {
        $arr = "(";
        for ($i = 0; $i < sizeof($vehicleType); $i++) {
            $arr = $arr . "'" . $vehicleType[$i] . "'";
            if ($i != sizeof($vehicleType) - 1)
                $arr = $arr . ", ";
        }
        $arr = $arr . ")";
        $selectSpecificType1 = "AND C1.VEHICLETYPE IN " . $arr;
        $selectSpecificType2 = "AND C2.VEHICLETYPE IN " . $arr;
    }

    $sql = " SELECT DISTINCT R1.LICENSEPLATENO, R1.MODELNAME, C1.VEHICLETYPE, 
    LISTAGG(O1.optionName, ', ') WITHIN GROUP (ORDER BY O1.optionName) OVER (PARTITION BY O1.licensePlateNo)
    FROM RENTCAR R1, CARMODEL C1, OPTIONS O1
    WHERE (NOT EXISTS (
        SELECT 1
        FROM RESERVATION RV
        WHERE (NOT (RV.ENDDATE < TO_DATE('" . $startDate . "', 'YYYY-MM-DD') 
            OR RV.STARTDATE > TO_DATE('" . $endDate . "', 'YYYY-MM-DD'))) 
        AND R1.LICENSEPLATENO = RV.LICENSEPLATENO) 
        AND R1.MODELNAME = C1.MODELNAME " . $selectSpecificType1 . "
        AND R1.LICENSEPLATENO = O1.LICENSEPLATENO)
    INTERSECT 
    SELECT DISTINCT R2.LICENSEPLATENO, R2.MODELNAME, C2.VEHICLETYPE, 
    LISTAGG(O2.optionName, ', ') WITHIN GROUP (ORDER BY O2.optionName) OVER (PARTITION BY O2.licensePlateNo)
    FROM RENTCAR R2, CARMODEL C2, OPTIONS O2
    WHERE (R2.DATERENTED IS NULL
        OR (R2.RETURNDATE < TO_DATE('" . $startDate . "', 'YYYY-MM-DD') 
        OR R2.DATERENTED > TO_DATE('" . $endDate . "', 'YYYY-MM-DD')))
        AND R2.MODELNAME = C2.MODELNAME " . $selectSpecificType2 . "
        AND R2.LICENSEPLATENO = O2.LICENSEPLATENO ORDER BY 2";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<tr> <td> <input type='radio' name='reserveRadio'> </td>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "<td>" . $row[3] . "</td>";
        echo "</tr>";
    }
}




    
?>