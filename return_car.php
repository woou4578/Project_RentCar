<?php 
session_start();
if (!isset($_SESSION['name'])) header('Location: login.php');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\SMTP;

// include("./PHPMailer/src/PHPMailer.php");
// include("./PHPMailer/src/Exception.php");
// include("./PHPMailer/src/SMTP.php");

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
$today = date('m/d/y', strtotime($_SESSION['todayDate']));

$sql = "INSERT INTO PREVIOUSRENTAL VALUES ('".$carNumber."', TO_DATE( '".$rentDate."', 'YY/MM/DD'), TO_DATE( '".$today."', 'MM/DD/YY'),".$payment.", '".$cno."')";
$stmt = $conn->prepare($sql);
$stmt -> execute();

$sql2 = "UPDATE RENTCAR SET DATERENTED = NULL, RETURNDATE = NULL, CNO = NULL WHERE LICENSEPLATENO = '".$carNumber."'";
$stmt = $conn->prepare($sql2);
$stmt -> execute();

// $from_id = "cnu.rentcar10@gmail.com";
// $pw = "cnurentcar10!";
// $title = "반납 완료 안내 메일 - CNU_RentCar";
// $article = "반납했습니다.";

// $smtp = 'smtp.office365.com';
// $mail = new PHPMailer(true);
// $mail->IsSMTP();
// try {
//     $mail->Host = $smtp;
//     $mail->SMTPAuth = true;
//     $mail->Port = 465;
//     $mail->SMTPSecure = "ssl";
//     $mail->Username = $from_id;
//     $mail->Password = $pw;
//     $mail->CharSet = "UTF-8";
//     $mail->SetFrom($from_id);
//     $mail->AddAddress("haijun79@naver.com");
//     $mail->Subject = $title;
//     $mail->MsgHTML($article);
//     $mail->Send();
// } catch (phpmailerException $e) {
//     echo $e->errorMessage();
// } catch (Exception $e) {
//     echo $e->getMessage();
// }
?>