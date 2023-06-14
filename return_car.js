const rented = document.getElementById('rented');
const returnButton = document.getElementById('returnButton');

function To_main() {
    location.href="./user_main.html";
}

function check_my_rent() {
    $.ajax({
        url: 'check_my_rent.php',
        type: 'GET',
        data: {},
        success: function (data) {
            rented.innerHTML = data;
            if(rented.children[0].childElementCount == 2) {
                alert('현재 렌트 중인 차량이 없습니다!');
                To_main();
            };
        },
        error: function (e) {
            alert(e.reponseText);
        }
    });
}

function return_car() {
    var table_value = rented.children[0].children[2];
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
            success:function() {
                alert('반납 완료!');
                location.href = 'user_main.html';
            },
            error:function(e) {
                alert(e.reponseText);
            }
        });
    }
}

returnButton.addEventListener("click", return_car);