<?php 
    session_start();
    if(!isset($_SESSION['name'])) header('Location: http://localhost/CNU_RENTCAR/login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>관리자 메인 화면</title>
</head>
<body>
    <h1>CNU RentCar</h1>
    <form action = "logout.php">
        <span>어서오세요! <?php echo $_SESSION['name']?> 님!</span>
        <button>로그아웃</button>
    </form>

    <div class='mainDiv'>
        <h2>검색</h2>
        <br><br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="firstBtn1" value="렌터카 검색"/><br/>
        </form>
        <br><br>
        <form method="post">
            <input type="submit" name="firstBtn2" value="통계 기록 보기"/><br/>
        </form>
        <br><br>
    </div>
</body>
</html>

<?php
    function movePage($addr) {echo "<script>location.href='".$addr."'</script>";}
    // submit의 name이 array_key_exists 에서 접근함
    if(array_key_exists('firstBtn1',$_POST)){movePage("searchRentCar.php");}
    if(array_key_exists('firstBtn2',$_POST)) {movePage("statistics.php");}
?>