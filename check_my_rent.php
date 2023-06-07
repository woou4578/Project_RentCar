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


$sql = "SELECT R.licensePlateNo, R.modelName, Car.vehicleType, R.dateRented, R.returnDate, (R.returnDate - R.dateRented + 1)*Car.rentRatePerDay FROM RentCar R, CarModel Car, Customer C
    WHERE C.name = ? AND C.cno = R.cno AND R.modelName = Car.modelName";
$stmt = $conn -> prepare($sql);
$stmt -> execute(array($_SESSION['name']));
$numnum = 0;
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $numnum ++;
}
if($numnum != 0) {
    echo "
    <tr>
        <th>차량번호</th>
        <th>모델명</th>
        <th>차종</th>
        <th>빌린 날짜</th>
        <th>반납 기한</th>
        <th>예상 결제 비용</th>
    </tr>";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute(array($_SESSION['name']));
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<td>".$row[0]."</td>";
        echo "<td>".$row[1]."</td>";
        echo "<td>".$row[2]."</td>";
        echo "<td>".$row[3]."</td>";
        echo "<td>".$row[4]."</td>";
        echo "<td>".$row[5]."</td>";
        echo "</tr>";
    }
}else {
    echo -1;
}
?>