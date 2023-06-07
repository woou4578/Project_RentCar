<?php 
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
?>

<?php 
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
}
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

$carNumber = $_GET['carNumber'];
$rentDate = $_GET['rentDate'];
$returnDate = $_GET['returnDate'];
$payment = $_GET['payment'];
$cno = $_SESSION['id'];


$sql = "INSERT INTO PREVIOUSRENTAL VALUES ('".$carNumber."',
            TO_DATE( '".$rentDate."', 'YY/MM/DD'),
            TO_DATE( '".$returnDate."', 'YY/MM/DD'),".
            $payment.", '".$cno."')";
$stmt = $conn->prepare($sql);
$stmt -> execute();

$sql2 = "UPDATE RENTCAR SET DATERENTED = NULL, RETURNDATE = NULL, CNO = NULL
        WHERE LICENSEPLATENO = '".$carNumber."'";
$stmt = $conn->prepare($sql2);
$stmt -> execute();

echo "반납되었습니다.";
?>

