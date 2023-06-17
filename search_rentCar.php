<?php
// 로그인 확인 
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// DB 연결 설정
$tns = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'CNU_RENTCAR';
$password = '0000';
$ModelOrType = $_GET['ModelOrType'] ?? '';
$rentRatePerDay = $_GET['rentRatePerDay'] ?? 170000;
$numberOfSeats = $_GET['numberOfSeats'] ?? 5;
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}
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
    <title>렌트카 검색 화면</title>
</head>

<body>
    <h1 onclick="To_main()"><Img src="./Logo.png"></Img></h1>           <!-- 메인 배너 -->
    <form action="logout.php" style="position: absolute; right: 30px;"> <!-- 회원 정보 & 로그아웃 버튼 -->
        <span id=<?php echo $_SESSION['name'] ?>>어서오세요! <?php echo $_SESSION['name'] ?> 님!</span>
        <button>로그아웃</button>
    </form>
    <br>
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
                <th>옵션</th>
                <th>렌트 가능 여부</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 모든 렌트카들 정보 검색( 검색 조건이 있다면 적용해서 )
            $sql = "SELECT DISTINCT R.licensePlateNo, R.modelName, C.vehicleType, C.rentRatePerDay, C.fuel, C.numberOfSeats, LISTAGG(O.OPTIONNAME, ', ') WITHIN GROUP (ORDER BY O.OPTIONNAME) OVER (PARTITION BY O.licensePlateNo), decode(R.dateRented, NULL, 'O', 'X') 가능여부 
        FROM RentCar R, CarModel C, Options O WHERE R.modelName = C.modelName AND R.licensePlateNo = O.licensePlateNo AND ((R.modelName LIKE '%' || :ModelOrType || '%') OR (C.vehicleType LIKE '%' || :ModelOrType || '%')) AND (C.rentRatePerDay <= :rentRatePerDay) AND (C.numberOfSeats >= :numberOfSeats) ORDER BY 8, 4 DESC,2";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ModelOrType', $ModelOrType, PDO::PARAM_STR);
            $stmt->bindParam(':rentRatePerDay', $rentRatePerDay, PDO::PARAM_STR);
            $stmt->bindParam(':numberOfSeats', $numberOfSeats, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                ?>
                <tr>
                    <td>
                        <?= $row[0] ?>
                    </td>
                    <td>
                        <?= $row[1] ?>
                    </td>
                    <td>
                        <?= $row[2] ?>
                    </td>
                    <td>
                        <?= $row[3] ?>
                    </td>
                    <td>
                        <?= $row[4] ?>
                    </td>
                    <td>
                        <?= $row[5] ?>
                    </td>
                    <td>
                        <?= $row[6] ?>
                    </td>
                    <td>
                        <?= $row[7] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <form>
        <div> <!-- 모델명 또는 차종으로 검색 -->
            <label for="ModelOrType">모델명 또는 차종 : </label>
            <input type="text" id="ModelOrType" name="ModelOrType" placeholder="모델명 또는 차종" value="<?= $ModelOrType ?>">
        </div>
        <br>
        <div> <!-- 최대 렌트 비용 검색 -->
            <label for="rentRatePerDay">최대 렌트 비용 : </label>
            <input type="number" id="rentRatePerDay" name="rentRatePerDay" placeholder="최대 렌트 비용"
                value="<?= $rentRatePerDay ?>">
        </div>
        <br>
        <div> <!-- 최소 좌석 수 검색 -->
            <label for="ModelOrType">최소 좌석 수 : </label>
            <input type="number" id="numberOfSeats" name="numberOfSeats" placeholder="최소 좌석 수"
                value="<?= $numberOfSeats ?>">
        </div>
        <br>
        <div>
            <button type="submit">검색</button>
        </div>
    </form>
</body>

</html>
<script>
    // 배너 클릭시 메인 화면으로 이동
    function To_main() {
        if (document.getElementsByTagName("span")[0].getAttribute("id") == '관리자') location.href = "./root_main.php";
        else location.href = "./user_main.html";
    }
</script>