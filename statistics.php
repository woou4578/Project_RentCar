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
    <title>렌트카 통계 화면</title>
</head>

<body>
    <h1 onclick="To_main()"><Img src="./Logo.png"></Img></h1> <!-- 메인 배너 -->

    <h2 onclick="toggle_div('1')">렌트카별 이전 렌트 횟수와 총 수입 ▼</h2> <!-- 첫 번째 질의문 -->
    <div style="display:none" id='1'>
        <table>
            <thead>
                <tr>
                    <th>모델명</th>
                    <th>차번호</th>
                    <th>이전 렌트 횟수</th>
                    <th>총 수입</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 조인과 그룹 함수 이용하여 검색
                $sql = "SELECT DECODE(GROUPING(R.modelName), 1, '모든 모델', R.modelName), DECODE(GROUPING(P.licensePlateNo), 1, '모든 차번호', P.licensePlateNo), count(*), sum(P.payment)
                FROM PreviousRental P, RentCar R, CarModel C WHERE P.licensePlateNo = R.licensePlateNo AND R.modelName = C.modelName GROUP BY ROLLUP (R.modelName, P.licensePlateNo)";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // 결과 출력
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
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <h2 onclick="toggle_div('2')">최근 반납된 렌트카 기록 (10대) ▼</h2> <!-- 두 번째 질의문 -->
    <div style="display:none" id='2'>
        <table>
            <thead>
                <tr>
                    <th>모델명</th>
                    <th>차번호</th>
                    <th>빌린 날짜</th>
                    <th>반납 날짜</th>
                    <th>아이디</th>
                    <th>이름</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 조인하여 상위 10개만 추출하여 검색
                $sql = "SELECT Car.modelName, P.licensePlateNo, P.dateReturned, P.dateRented, C.cno, C.name FROM PreviousRental P, RentCar R, CarModel Car, Customer C
                    WHERE P.licensePlateNo = R.licensePlateNo AND R.modelName = Car.modelName AND P.cno = C.cno ORDER BY dateRented DESC FETCH FIRST 10 ROWS ONLY";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // 결과 출력
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
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <h2 onclick="toggle_div('3')">고객별 렌트 횟수와 총 결제 금액 ▼</h2> <!-- 세 번째 질의문 -->
    <div style="display:none" id='3'>
        <table>
            <thead>
                <tr>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>렌트 횟수</th>
                    <th>총 결제 금액</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 조인 활용하여 출력
                $sql = "SELECT AA.아이디, AA.이름, SUM(횟수) 렌트횟수, SUM(금액) 총금액
                FROM (SELECT P1.CNO 아이디, C1.NAME 이름, COUNT(P1.CNO) 횟수, SUM(PAYMENT) 금액
                    FROM PREVIOUSRENTAL P1, CUSTOMER C1
                    WHERE P1.CNO = C1.CNO
                    GROUP BY P1.CNO, C1.NAME
                    UNION
                    SELECT R1.CNO 아이디, C2.NAME 이름, COUNT(R1.CNO) 횟수, SUM(0) 금액
                    FROM RENTCAR R1, CUSTOMER C2
                    WHERE R1.CNO IS NOT NULL AND R1.CNO = C2.CNO
                    GROUP BY R1.CNO, C2.NAME) AA
                GROUP BY AA.아이디, AA.이름
                ORDER BY 3 DESC, 4 DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // 결과 출력
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
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <h2 onclick="toggle_div('4')">회사에서 인기많은 차량과 순위 ▼</h2> <!-- 네 번째 질의문 -->
    <div style="display:none" id='4'>
        <table>
            <thead>
                <tr>
                    <th>모델명</th>
                    <th>총 렌트 횟수</th>
                    <th>인기 순위</th>
                    <th>총 수입</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 조인과 윈도우 함수 활용한 검색
                $sql = "SELECT AA.모델명, AA.횟수, RANK() OVER (ORDER BY AA.횟수 DESC), AA.수익
                    FROM (SELECT R1.MODELNAME 모델명, COUNT(*) 횟수, SUM(PAYMENT) 수익 FROM PREVIOUSRENTAL P1, RENTCAR R1 WHERE P1.LICENSEPLATENO = R1.LICENSEPLATENO GROUP BY R1.MODELNAME) AA";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // 결과 출력
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
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<script>
    function To_main() {    // 메인 화면으로 이동
        location.href = "./root_main.php";
    }
    function toggle_div(id) {   // 클릭 시 보였다가 안 보였다가 전환
        var e = document.getElementById(id);
        e.style.display = ((e.style.display != 'none') ? 'none' : 'block');
    }
</script>