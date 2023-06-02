<?php 
  session_start();
  if(!session_id()) header('Location: http://localhost/CNU_RENTCAR/login.php');

  session_destroy();
  echo "<script>alert('로그아웃 되었습니다.');</script>";
  echo "<script>location.href='login.php'</script>";
?>