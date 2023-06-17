const cancelButton = document.getElementById("cancelButton");
const reservedTable = document.getElementById("reservedTable");

// 실제로 예약 취소를 동작하는 함수
function cancel_my_reservation() {
    const varRadio = document.getElementsByName("cancelRadio");
    var selectedIndex = -1;
    for (let i = 0; i < varRadio.length; i++) {
        // 선택된 radio값을 확인한다.
        if (varRadio[i].checked === true) {
            selectedIndex = i;
            break;
        }
    }
    var selectedElement = reservedTable.children[0].children[selectedIndex + 1].children[4];
    var selectedStartDate = selectedElement.innerHTML;
    if (selectedStartDate.length > 0) {
        // php파일에 접근해 예약 취소를 진행한다.
        $.ajax({
            url: 'cancel_reservation.php',
            type: 'GET',
            data: {
                startDate: selectedStartDate
            },
            success: function (data) {
                alert(data);
                window.location.href = 'user_main.html';
            },
            error: function (e) {
                alert(e.reponseText);
            }
        });
    }
}

// 나의 예약 정보를 확인하는 함수
function show_my_reservation() {
    $.ajax({
        // php를 통해 나의 예약 정보를 불러온다.    
        url: 'check_my_reservation.php',
        type: 'GET',
        data: {},
        success: function (data) {
            document.getElementById("reservedTable").innerHTML = data;
        },
        error: function (e) {
            alert(e.reponseText);
        }
    });
}

// 메인으로 이동하는 함수
function To_main() {
    location.href = "user_main.html";
}

cancelButton.addEventListener("click", cancel_my_reservation);