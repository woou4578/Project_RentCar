<?php 
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>

<?php 
$startDate = $_GET['firstDate'] ?? '';
$endDate = $_GET['secondDate'] ?? '';
$carNumber = $_GET['carNumber'] ?? '';

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

$today = date('m/d/y', strtotime("20230502"));
$newStartDate = date('m/d/y', strtotime($startDate));
$newEndDate = date('m/d/y', strtotime($endDate));

$sql2 = "INSERT INTO RESERVATION VALUES ('" . $carNumber . "',
            TO_DATE( '" . $today . "' , 'MM/DD/YY'),
            TO_DATE( '" . $newStartDate . "' , 'MM/DD/YY'),
            TO_DATE( '" . $newEndDate . "' , 'MM/DD/YY'),
            '" . $_SESSION['id'] . "')";
$stmt = $conn->prepare($sql2);
$stmt -> execute();

echo "예약하였습니다.";
?>