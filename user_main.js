const changeDateButton = document.getElementById('changeDateButton');

// 회원정보 & 오늘 날짜 설정 
function startSetting() {
    getNowName();
    getNowDate();
}

// get_name.php로 회원 정보 갖고오기
function getNowName() {
    $.ajax({
        url: 'get_name.php',
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

// get_today_date.php로 오늘 날짜 갖고오기
function getNowDate() {
    $.ajax({
        url: 'get_today_date.php',
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

// get_next_date.php로 다음 날짜로 변경하기
function getNextDate() {
    if (confirm("날짜를 바꾸시겠습니까?")) {
        $.ajax({
            url: 'get_next_date.php',
            type: 'GET',
            data: {
                val: 1
            },
            success: function (data) {
                document.getElementById('todayDate').innerHTML = data;
            },
            error: function (e) {
                alert(e.reponseText);
            }
        });
    }
}

changeDateButton.addEventListener('click', getNextDate);