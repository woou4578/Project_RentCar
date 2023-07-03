<?php
// 경로에 존재하는 파일 이용
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 사용 : mailer(받을 이메일 주소, 제목, 내용);
function mailer($to_id, $tit, $cont)
{
  include("./PHPMailer/src/PHPMailer.php");
  include("./PHPMailer/src/Exception.php");
  include("./PHPMailer/src/SMTP.php");

  // 발신자 정보 & 이메일 내용 구성
  $from_id = "cnu.rentcar10@gmail.com";
  $pw = ""; //전달받은 비밀번호는 입력
  $title = $tit;
  $article = $cont;

  $smtp = 'smtp.gmail.com';
  $mail = new PHPMailer(true);
  $mail->IsSMTP();

  // 정보 담아서 송신
  try {
    $mail->Host = $smtp;
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->Username = $from_id;
    $mail->Password = $pw;
    $mail->CharSet = "UTF-8";
    $mail->SetFrom($from_id);
    $mail->AddAddress($to_id);
    $mail->Subject = $title;
    $mail->MsgHTML($article);
    $mail->Send();
  } catch (phpmailerException $e) {
    echo $e->errorMessage();
  } catch (Exception $e) {
    echo $e->getMessage();
  }
}
?>
