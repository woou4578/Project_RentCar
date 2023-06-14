<?php 
    session_start();
    if(!isset($_SESSION['name'])) header('Location: login.php');
    $tns = "
    (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
    $url = "oci:dbname=".$tns.";charset=utf8";
    $username = 'CNU_RENTCAR';
    $password = '0000';
    $startDate = $_GET['startDate'] ?? "2023-01-01";
    $endDate = $_GET['endDate'] ?? "2023-06-01";
    try {
        $conn = new PDO($url, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>이전 대여 기록 화면</title>
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
        tr, td, th {
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
</head>
<body>
    <h1 onclick="To_main()"><Img src="./Logo.png"></Img></h1>
    <form action = "logout.php" style="position: absolute; right: 30px;">
        <span>어서오세요! <?php echo $_SESSION['name']?> 님!</span>
        <button>로그아웃</button>
    </form>
    <br>
    <h2>나의 이전 대여 내역</h2>
    <table>
        <thead>
            <tr>
                <th>차번호</th>
                <th>모델명</th>
                <th>차종</th>
                <th>대여 일자</th>
                <th>반납 일자</th>
                <th>지불 비용</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if(strtotime($startDate) > strtotime($endDate)) {
        echo "<script>alert('날짜를 확인해주세요');
        location.href='./previous_rental.php';</script>";
    }
    $sql = "SELECT P.licensePlateNo, R.modelName, Car.vehicleType, P.dateRented, P.dateReturned, P.payment FROM PreviousRental P, RentCar R, CarModel Car, Customer C
        WHERE C.cno = :cno AND C.cno = P.cno AND P.licensePlateNo = R.licensePlateNo AND R.modelName = Car.modelName AND TO_DATE(:startDate, 'YYYY/MM/DD') <= P.dateRented AND P.dateReturned <= TO_DATE(:endDate, 'YYYY/MM/DD')
        ORDER BY 5 DESC";
    $stmt = $conn -> prepare($sql);
    $stmt -> bindParam(':cno', $_SESSION['id'], PDO::PARAM_STR);
    $stmt -> bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt -> bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stmt -> execute();
    $num = 0;
    while ($row = $stmt -> fetch(PDO::FETCH_NUM)) {
    ?>
        <tr>
            <td><?= $row[0] ?></td>
            <td><?= $row[1] ?></td>
            <td><?= $row[2] ?></td>
            <td><?= $row[3] ?></td>
            <td><?= $row[4] ?></td>
            <td><?= $row[5] ?></td>
            <?php $num++; ?>
        </tr>
    <?php } 
    if ($num == 0) {
        echo "<script>alert('날짜를 확인해주세요');
        location.href='./previous_rental.php';</script>";
    }
    ?>
        </tbody>
    </table>
    <br>
    <form>
        <div>
            <label for="startDate"> 검색 시작 날짜 : </label>
            <input type="date" id="startDate" name="startDate" value="<?= $searchWord ?>"> ~ 
            <label for="endDate"> 검색 종료 날짜 : </label>
            <input type="date" id="endDate" name="endDate" value="<?= $searchWord ?>">
        </div>
        <br>
        <div>
            <button type="submit">검색</button>
        </div>
    </form>
</body>
</html>

<script>
    function To_main() {
        if(document.getElementsByTagName("span")[0].getAttribute("id") == '관리자') location.href="./root_main.php";
        else location.href="./user_main.html";
    }
</script>