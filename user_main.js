const changeDateButton = document.getElementById('changeDateButton');

function startSetting() {
    getNowName();
    getNowDate();
}
function getNowName() {
    $.ajax({
        url: 'getName.php',
        type: 'GET',
        data: {},
        success: function (data) {
            document.getElementById('nowName').innerHTML = data;
        },
        error: function (e) {
            alert(e.reponseText);
        }
    });
}
function getNowDate() {
    $.ajax({
        url: 'getTodayDate.php',
        type: 'GET',
        data: {
            val: 0
        },
        success: function (data) {
            document.getElementById('todayDate').innerHTML = data;
        },
        error: function (e) {
            alert(e.reponseText);
        }
    });
}
function getNextDate() {
    if(confirm("날짜를 바꾸시겠습니까?")){
        $.ajax({
            url: 'getNextDate.php',
            type: 'GET',
            data: {
                val: 1
            },
            success: function (data) {
                console.log("여기래");
                document.getElementById('todayDate').innerHTML = data;
            },
            error: function (e) {
                alert(e.reponseText);
            }
        });
    }
}

changeDateButton.addEventListener('click', getNextDate);