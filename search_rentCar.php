<?php 
    session_start();
    if(!isset($_SESSION['name'])) header('Location: http://localhost/CNU_RENTCAR/login.php');
    $tns = "
    (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
    $url = "oci:dbname=".$tns.";charset=utf8";
    $username = 'CNU_RENTCAR';
    $password = '0000';
    $searchWord = $_GET['searchWord'] ?? '';
    try {
        $conn = new PDO($url, $username, $password);
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>렌트카 검색 화면</title>
</head>
<body>
    <h1 onclick="To_main()">CNU RentCar</h1>
    <form action = "logout.php">
        <span>어서오세요! <?php echo $_SESSION['name']?> 님!</span>
        <button>로그아웃</button>
    </form>

    <h2>렌트카 목록</h2>
    <table>
        <thead>
            <tr>
                <th>차량번호</th>
                <th>모델명</th>
                <th>차종</th>
                <th>하루 렌트 비용</th>
                <th>연료</th>
                <th>좌석 수</th>
                <th>렌트 가능 여부</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if(is_numeric($searchWord)!=1) {
        $sql = "SELECT R.licensePlateNo, R.modelName, C.vehicleType, C.rentRatePerDay, C.fuel, C.numberOfSeats, decode(R.dateRented, NULL, 'O', 'X') 가능여부 FROM RentCar R, CarModel C 
            WHERE R.modelName = C.modelName AND ((R.modelName LIKE '%' || :searchWord || '%') OR (C.vehicleType LIKE '%' || :searchWord || '%') OR (C.fuel LIKE '%' || :searchWord || '%')) ORDER BY 가능여부";
    }
    else {
        $sql = "SELECT R.licensePlateNo, R.modelName, C.vehicleType, C.rentRatePerDay, C.fuel, C.numberOfSeats, decode(R.dateRented, NULL, 'O', 'X') 가능여부 FROM RentCar R, CarModel C 
            WHERE R.modelName = C.modelName AND ((R.licensePlateNo LIKE '%' || :searchWord || '%') OR (C.rentRatePerDay LIKE '%' || :searchWord || '%') OR (C.numberOfSeats LIKE '%' || :searchWord || '%')) ORDER BY 가능여부";
    }
    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array($searchWord));
    while ($row = $stmt -> fetch(PDO::FETCH_NUM)) {
    ?>
        <tr>
            <td><?= $row[0] ?></td>
            <td><?= $row[1] ?></td>
            <td><?= $row[2] ?></td>
            <td><?= $row[3] ?></td>
            <td><?= $row[4] ?></td>
            <td><?= $row[5] ?></td>
            <td><?= $row[6] ?></td>
        </tr>
    <?php } ?>
        </tbody>
    </table>

    <form>
        <div>
            <label for="searchWord">검색 내용 : </label>
            <input type="text" id="searchWord" name="searchWord" placeholder="검색어 입력" value="<?= $searchWord ?>">
        </div>
        <div>
            <button type="submit">검색</button>
        </div>
    </form>
</body>
</html>
<script>
    function To_main() {
        if(document.getElementsByTagName("span")[0].getAttribute("id") == '관리자') location.href="./root_main.php";
        else location.href="./user_main.php";
    }
</script>