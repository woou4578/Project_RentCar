<?php 
    session_start();
    if(!isset($_SESSION['name'])) header('Location: login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>고객 메인 화면</title>
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
            <input type="submit" name="firstBtn2" value="나의 이전 렌탈 내역"/><br/>
        </form>
        <br><br>
    </div>
    
    <div class='mainDiv'>
        <h2>예약 / 반납</h2>
        <br><br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="secondBtn1" value="예약하기"/><br/>
        </form>
        <br><br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="secondBtn2" value="예약 취소"/><br/>
        </form>
        <br><br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="secondBtn3" value="반납"/><br/>
        </form>
    </div>
</body>
</html>

<?php
    function movePage($addr) {echo "<script>location.href='".$addr."'</script>";}
    // submit의 name이 array_key_exists 에서 접근함
    if(array_key_exists('firstBtn1',$_POST)) {movePage("search_rentCar.php");}
    if(array_key_exists('firstBtn2',$_POST)) {movePage("previous_rental.php");}
    if(array_key_exists('secondBtn1',$_POST)) {movePage("reserve.html");}
    if(array_key_exists('secondBtn2',$_POST)) {movePage("cancel_reservation.html");}
    if(array_key_exists('secondBtn3',$_POST)) {movePage("return_car.html");}
?>