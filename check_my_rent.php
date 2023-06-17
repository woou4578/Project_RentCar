<?php
// 로그인 확인 부분
session_start();
if (!isset($_SESSION['name']))
    header('Location: login.php');

// DB 연결
$tns = "
    (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA=(SERVICE_NAME=XE)))";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'CNU_RENTCAR';
$password = '0000';
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

$today = date('m/d/y', strtotime($_SESSION['todayDate']));

echo "<tr>
        <th rowspan='2'>차량번호</th>
        <th rowspan='2'>모델명</th>
        <th rowspan='2'>차종</th>
        <th rowspan='2'>빌린 날짜</th>
        <th rowspan='2'>반납 기한</th>
        <th colspan='2'>예상 결제 비용</th>
    </tr> 
    <tr>
        <td>금일 반납</td>
        <td>말일 반납</td>
    </tr>";

// 반납 가능한 차량을 DB에서 불러와 데이터의 정보를 조회하는 부분
$sql = "SELECT R.licensePlateNo, R.modelName, Car.vehicleType, R.dateRented, R.returnDate, (TO_DATE(:todayDate, 'MM/DD/YY') - R.dateRented + 1)*Car.rentRatePerDay, (R.returnDate - R.dateRented + 1)*Car.rentRatePerDay 
    FROM RentCar R, CarModel Car, Customer C WHERE C.name = :name AND C.cno = R.cno AND R.modelName = Car.modelName";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':todayDate', $today, PDO::PARAM_STR);
$stmt->bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
$stmt->execute();
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . $row[4] . "</td>";
    echo "<td>" . $row[5] . "</td>";
    echo "<td>" . $row[6] . "</td>";
    echo "</tr>";
    $count++;
}
?>