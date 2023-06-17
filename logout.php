<!-- 로그아웃 버튼 클릭 시 -->

<?php
// 메인 화면으로 이동
session_start();
if (!session_id())
  header('Location: login.php');

// 세션 파괴 후 알림창 안내
session_destroy();
echo "<script>alert('로그아웃 되었습니다.');</script>";
echo "<script>location.href='login.php'</script>";
?>