const rented = document.getElementById('rented');
const returnButton = document.getElementById('returnButton');

function To_main() {
    location.href="./user_main.php";
}

function check_my_rent() {
    $.ajax({
        url: 'check_my_rent.php',
        type: 'GET',
        data: {},
        success: function (data) {
            if(data == -1) {
                alert("nono");
                location.href="./user_main.php";
            }else {
                alert('yesyes');
                rented.innerHTML = data;
            }
        },
        error: function (e) {
            alert(e.reponseText);
        }
    });
    
    
}

function return_car() {
    if(rented.childNodes.length != 0) {
        var table_value = rented.children[0].children[1];
        var carNum = table_value.children[0].innerHTML ?? '';
        var firstDate = table_value.children[3].innerHTML ?? '';
        var secondDate = table_value.children[4].innerHTML ?? '';
        var cost = table_value.children[5].innerHTML ?? '';
        if(carNum.length > 0) {
            $.ajax({    
                url: 'return_car.php',
                type: 'GET',
                data: { 
                    carNumber: carNum,
                    rentDate: firstDate,
                    returnDate: secondDate,
                    payment: cost
                },
                success:function(data) {
                    alert(data);
                    location.href = 'user_main.php';
                },
                error:function(e) {
                    alert(e.reponseText);
                }
            });
        }
    }else {
        alert("데이터가 없습니다.");
    }
}
if(returnButton) {
    returnButton.addEventListener("click", return_car);
}