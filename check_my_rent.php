<?php 
session_start();
if (!isset($_SESSION['name'])) header('Location: login.php');
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
        <td>오늘까지</td>
        <td>기한까지</td>
    </tr>";

$sql = "SELECT R.licensePlateNo, R.modelName, Car.vehicleType, R.dateRented, R.returnDate, (TO_DATE(:todayDate, 'MM/DD/YY') - R.dateRented + 1)*Car.rentRatePerDay, (R.returnDate - R.dateRented + 1)*Car.rentRatePerDay 
    FROM RentCar R, CarModel Car, Customer C WHERE C.name = :name AND C.cno = R.cno AND R.modelName = Car.modelName";
$stmt = $conn -> prepare($sql);
$stmt -> bindParam(':todayDate', $today, PDO::PARAM_STR);
$stmt -> bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
$stmt -> execute();
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo "<tr>";
    echo "<td>".$row[0]."</td>";
    echo "<td>".$row[1]."</td>";
    echo "<td>".$row[2]."</td>";
    echo "<td>".$row[3]."</td>";
    echo "<td>".$row[4]."</td>";
    echo "<td>".$row[5]."</td>";
    echo "<td>".$row[6]."</td>";
    echo "</tr>";
    $count++;
}
?>