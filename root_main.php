<!-- 관리자 메인 화면 -->

<?php
// 로그인 확인 
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style type="text/css">
        * {
            font-size: 20px;
        }

        body {
            margin: 30px;
        }

        table {
            width: 100%;
            border: 1px solid #444;
            border-collapse: collapse;
            text-align: center;
            box-shadow: 1px 1px;
        }

        tr,
        td,
        th {
            border: 1px solid #444;
            padding: 4px;
        }

        button {
            font-size: 15px;
        }

        h2 {
            margin-left: 15px;
            font-size: 27px;
        }
    </style>
    <title>관리자 메인 화면</title>
</head>

<body>
    <h1><Img src="./Logo.png"></Img></h1>                                   <!-- 메인 배너 -->
    <form action="logout.php" style="position: absolute; right: 30px;">     <!-- 회원 정보 & 로그아웃 버튼 -->
        <span>어서오세요!
            <?php echo $_SESSION['name'] ?> 님!
        </span>
        <button>로그아웃</button>
    </form>
    <br>
    <div class='mainDiv'>                                                   <!-- 페이지 맵 -->
        <h2>검색</h2>
        <br><br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="firstBtn1" value="렌터카 검색" /><br />
        </form>
        <br><br>
        <form method="post">
            <input type="submit" name="firstBtn2" value="통계 기록 보기" /><br />
        </form>
        <br><br>
    </div>
</body>

</html>

<?php
// 클릭 시 해당 페이지로 이동
function movePage($addr) {
    echo "<script>location.href='" . $addr . "'</script>";
}
// submit의 name이 array_key_exists 에서 접근함
if (array_key_exists('firstBtn1', $_POST)) {
    movePage("search_rentcar.php");
}
if (array_key_exists('firstBtn2', $_POST)) {
    movePage("statistics.php");
}
?>