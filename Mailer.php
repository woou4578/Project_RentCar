<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// 사용 : mailer(보낼 이메일, 제목, 내용);
function mailer($to_id, $tit, $cont){
  include("./PHPMailer/src/PHPMailer.php");
  include("./PHPMailer/src/Exception.php");
  include("./PHPMailer/src/SMTP.php");

  $from_id="cnu.rentcar10@gmail.com";
  $pw="xlqjcdogcihihedf";
  $title= $tit;
  $article=$cont;
  
  $smtp='smtp.gmail.com';
  $mail=new PHPMailer(true);
  $mail->IsSMTP();
  
  try{
    $mail->Host=$smtp;
    $mail->SMTPAuth=true;
    $mail->Port=465;
    $mail->SMTPSecure="ssl";
    $mail->Username=$from_id;
    $mail->Password=$pw;
    $mail->CharSet = "UTF-8";
    $mail->SetFrom($from_id);
    $mail->AddAddress($to_id);
    $mail->Subject=$title;
    $mail->MsgHTML($article);
    $mail->Send();
  } catch (phpmailerException $e){
  echo $e->errorMessage();
  } catch (Exception $e){
  echo $e->getMessage();
  }
}
?>