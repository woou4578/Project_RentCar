const cancelButton = document.getElementById("cancelButton");
const reservedTable = document.getElementById("reservedTable");

function cancel_my_reservation() {
    const varRadio = document.getElementsByName("cancelRadio");
    var selectedIndex = -1;
    for(let i = 0; i < varRadio.length; i++) {
        if(varRadio[i].checked === true) {
            selectedIndex = i;
            break;
        }
    }
    var selectedElement = reservedTable.children[0].children[selectedIndex+1].children[3];
    var selectedStartDate = selectedElement.innerHTML;
    alert(selectedStartDate);
    if(selectedStartDate.length > 0) {
        $.ajax({    
            url: 'cancel_reservation.php',
            type: 'GET',
            data: { 
                startDate : selectedStartDate
            },
            success:function(data) {
                alert(data);
                window.location.href = 'user_main.php';
            },
            error:function(e) {
                alert(e.reponseText);
            }
        });
    }
}

function show_my_reservation() {
    $.ajax({    
        url: 'reserved_table.php',
        type: 'GET',
        data: { },
        success:function(data) {
            document.getElementById("reservedTable").innerHTML = data;
        },
        error:function(e) {
            alert(e.reponseText);
        }
    });
}

function To_main() {
    if(document.getElementsByTagName("span")[0].getAttribute("id") == "관리자"){
        location.href = "root_main.php";
    }else {
        location.href = "user_main.php";
    }
}

cancelButton.addEventListener("click", cancel_my_reservation);