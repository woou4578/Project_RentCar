<?php
$tns = "
   (DESCRIPTION=
      (ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
      (CONNECT_DATA=(SERVICE_NAME=XE))
   )
";
$url = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'term_project';
$password = '457845';
$searchWord = $_POST['ID'] ?? '';
try {
    $conn = new PDO($url, $username, $password);
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

function movePage($id)
{
    
    if ($id == "root") {
        echo "<script>location.href='mainForRoot.php'</script>";
    } else {
        echo "<script>location.href='mainForCustomer.php'</script>";
    }
}




echo "<script>alert('{$searchWord}');</script>";
if (array_key_exists('firstBtn1', $_POST)) {
    $selectCustomer = "SELECT CNO, NAME, EMAIL FROM CUSTOMER WHERE CNO LIKE '%' || :firstBtn1 || '%' ORDER BY CNO";
    $stmt = $conn->prepare($selectCustomer);
    $stmt->execute(array($searchWord));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 여기서 비밀번호와 일치하는지 확인하면 됨
        
        echo "<script>alert('{$row['CNO']}');</script>";
    }
    movePage($_POST['ID']);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>CNU RentCar 로그인</title>
</head>

<body>

    <h3 style="text-align: center">CNU RentCar</h3>

    <form method="post" style="text-align: center"> <!-- 로그인/로그아웃 -->
        <label for="ID">아이디</label>
        <input type="text" id="ID" name="ID" placeholder="아이디"> <br><br>
        <label for="PWD">비밀번호</label>
        <input type="password" id="PWD" name="PWD" placeholder="비밀번호"> <br><br>
        <input type="submit" name="firstBtn1" value="로그인"></button>
    </form>

</body>

</html>



<?php

?>