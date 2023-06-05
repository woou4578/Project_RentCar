const searchButton = document.getElementById("searchButton")
var startDate = document.getElementById("startDate");
var endDate = document.getElementById("endDate");
var vehicleType = document.querySelector('#mySelect');
var reserveDiv = document.getElementById('reserveDiv');
const reserveTable = document.getElementById('reserveTable');
const reserveButton = document.getElementById("reserveButton");


function check_valid_input() {
    if((startDate.value == "" || endDate.value == "") || startDate.value > endDate.value) {
        alert("날짜를 제대로 입력해주세요.");   
        return false;
    }else if(vehicleType.value == "") {
        alert("차종을 제대로 선택해주세요.");
        return false;
    }
    return true;
}

function click_search() {
    if(check_valid_input()) {
        reserveDiv.style.display = "block";
        $.ajax({    
            url: 'reserve_table.php',
            type: 'GET',
            data: {
                firstDate : startDate.value,
                secondDate : endDate.value,
                vType : vehicleType.value
            },success:function(data) {
                document.getElementById("reserveTable").innerHTML = data;
            },
            error:function(e) {
                alert(e.reponseText);
            }
        });
    }
}

function add_reserve() {
    const varRadio = document.getElementsByName('reserveRadio');
    var selectedIndex = -1;
    for(let i = 0; i < varRadio.length; i++) {
        if(varRadio[i].checked === true) {
            selectedIndex = i;
            break;
        }
    }
    var selectedElement = reserveTable.children[0].children[selectedIndex+1].children[1];
    var selectedCarNumber = selectedElement.innerHTML;
    if(selectedCarNumber.length > 0) {
        $.ajax({    
            url: 'reserve.php',
            type: 'GET',
            data: {
                firstDate : startDate.value,
                secondDate : endDate.value,
                carNumber : selectedCarNumber
            },success:function(data) {
                alert(data);
                window.location.href = 'user_main.php';
            },
            error:function(e) {
                alert(e.reponseText);
            }
        });
    }
}

reserveButton.addEventListener("click", add_reserve);
searchButton.addEventListener("click", click_search);
