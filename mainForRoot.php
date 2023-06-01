<!DOCTYPE html>
<html lang="en">
<head>
    <title>관리자 메인 화면</title>
</head>
<body>
    <h1>CNU 렌터카</h1>

    <div class='mainDiv'>
        <h2>검색</h2>
        <br>
        <br>
        <form method="post">
            <!--submit의 name이 array_key_exists 에서 접근함 -->
            <input type="submit" name="firstBtn1" value="렌터카 검색"/><br/>
        </form>
        <br>
        <br>
        <form method="post">
            <input type="submit" name="firstBtn2" value="통계 기록 보기"/><br/>
        </form>
        <br>
        <br>
    </div>
</body>
</html>

<?php
    function movePage($addr) {
        echo "<script>location.href='".$addr."'</script>";
    }
    // submit의 name이 array_key_exists 에서 접근함
    if(array_key_exists('firstBtn1',$_POST)){
        movePage("searchRentCar.php");
    }
    if(array_key_exists('firstBtn2',$_POST)) {
        movePage("statistics.php");
    }
?>