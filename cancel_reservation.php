<?php 
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>

<?php 
$startDate = $_GET['startDate'] ?? '';

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
$sql2 = "DELETE FROM RESERVATION WHERE CNO = '".$ID."' AND
    STARTDATE = TO_DATE( '".$startDate."', 'YY/MM/DD')";

$stmt = $conn->prepare($sql2);
$stmt -> execute();

echo "예약 취소되었습니다.";
?>