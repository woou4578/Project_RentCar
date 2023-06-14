const searchButton = document.getElementById("searchButton")
var startDate = document.getElementById("startDate");
var endDate = document.getElementById("endDate");
var vehicleType = document.getElementsByName('vehicleType');
var reserveDiv = document.getElementById('reserveDiv');
const reserveTable = document.getElementById('reserveTable');
const reserveButton = document.getElementById("reserveButton");

function To_main() {
    if(document.getElementsByTagName("span")[0].getAttribute("id") == '관리자') location.href="./root_main.php";
    else location.href="./user_main.html";
}

function check_valid_input() {
    var isChecked = false;
    for(var i=0; i<vehicleType.length; i++) {
        if(vehicleType[i].checked == true) {
            isChecked = true;
            break;
        }
    }

    if((startDate.value == "" || endDate.value == "") || startDate.value > endDate.value) {
        alert("날짜를 제대로 입력해주세요.");   
        return false;
    }else if(!isChecked) {
        alert("차종을 제대로 선택해주세요.");
        return false;
    }
    return true;
}

function click_search() {
    if(check_valid_input()) {
        reserveDiv.style.display = "block";
        var arr = new Array();
        for(var i=0; i<vehicleType.length; i++) {
            if(vehicleType[i].checked == true) arr.push(vehicleType[i].value);
        }
    
        $.ajax({    
            url: 'reserve_table.php',
            type: 'GET',
            data: {
                firstDate : startDate.value,
                secondDate : endDate.value,
                vType : arr
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
                window.location.href = 'user_main.html';
            },
            error:function(e) {
                alert(e.reponseText);
            }
        });
    }
}

reserveButton.addEventListener("click", add_reserve);
searchButton.addEventListener("click", click_search);
