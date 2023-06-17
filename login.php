<!-- 이용자가 가장 처음으로 접근하는 로그인 창 -->

<?php
// 뒤로가기 같은 경우로 로그아웃 없이 접근 했을 때 
session_start();
if (isset($_SESSION['name']))
    session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        * {
            font-size: 20px;
            font-weight: bold;
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
    </style>
    <title>CNU RentCar 로그인</title>
</head>

<body>

    <h3 style="text-align: center"><Img src="./Logo.png"></Img></h3> <!-- 메인 배너 -->

    <form action="./login_check.php" method="post" style="text-align: center"> <!-- 로그인/로그아웃 -->
        <label for="ID">아이디</label>
        <input type="text" id="ID" name="ID" placeholder="아이디"> <br><br>
        <label for="PWD">비밀번호</label>
        <input type="password" id="PWD" name="PWD" placeholder="비밀번호"> <br><br>
        <button type="submit">로그인</button>
    </form>
</body>

</html>