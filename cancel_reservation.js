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
    var selectedElement = reservedTable.children[0].children[selectedIndex+1].children[4];
    var selectedStartDate = selectedElement.innerHTML;
    if(selectedStartDate.length > 0) {
        $.ajax({    
            url: 'cancel_reservation.php',
            type: 'GET',
            data: { 
                startDate : selectedStartDate
            },
            success:function(data) {
                // alert(data);
                // document.location.href = 'user_main.php';
                // alert(date.length);
                setTimeout(location.href='user_main.php', 1000);
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
    location.href = "user_main.php";
}

window.onload = function(){
    cancelButton.addEventListener("click", cancel_my_reservation);
}