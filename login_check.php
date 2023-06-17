<?php
// 로그인 확인
session_start();
if (!session_id())
    header('Location: login.php');

// DB 연결 설정
$tns = "(DESCRIPTION=
		(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
		(CONNECT_DATA=(SERVICE_NAME=XE)))";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'CNU_RENTCAR';
$password = '0000';
$ID = $_POST['ID'];
$PWD = $_POST['PWD'];
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

// 아이디 & 비밀번호 확인
$stmt = $conn->prepare("SELECT name, email FROM Customer WHERE cno = ? AND passwd = ?");
$stmt->execute(array($ID, $PWD));
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $_SESSION['name'] = $row[0];
    $_SESSION['email'] = $row[1];
    $_SESSION['id'] = $ID;
}

// 오늘 날짜 설정
$_SESSION['todayDate'] = '2023-06-21';

// 확인 결과가 없을 경우
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    echo ("<script>
                alert('아이디와 비밀번호를 확인해주세요!');
                location.replace('login.php');
            </script>");
    session_destroy();
}
// 확인 결과가 있을 경우
else {
    // 관리자 일때 root_main으로
    if ($_SESSION['name'] == '관리자')
        header('Location: root_main.php');
    // 이용자 일때 user_main으로
    else
        header('Location: user_main.html');
}
?>