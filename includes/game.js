function kick_player(kick = false){
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/clear_session.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'room_id=' + encodeURIComponent(room_id);
    xhr.send(data);
    localStorage.clear();
    if (kick) {
        window.location.href = "../index.php"
    }
}

function updateTheme(current_round){
    $.ajax({
        url: '../includes/get_theme.php',
        data: {room_id: room_id, current_round: current_round},
        success: function(response){
          $('.theme').html(response); 
        }
    });
}

function updateImages(){
    $(document).ready(function() {
        let image_list = [];
        if (localStorage.getItem("images_list")) {
            image_list = JSON.parse(localStorage.getItem("images_list"));
        } else {
            image_list = [];
            while (image_list.length < 6) {
                let random_number = Math.floor(Math.random() * 24) + 1;
                if (image_list.indexOf(random_number) === -1) {
                    image_list.push(random_number);
                }
            }
            localStorage.setItem("images_list", JSON.stringify(image_list));
        }
        $(".image_button img").each(function(index, element) {
          $(element).attr("src", `../images/image${image_list[index]}.png`);
        });
    });
} 

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function addSecondsToTime(start_time, secondsToAdd) {
    const cont_time = start_time.split(':'); 
    let hours = parseInt(cont_time[0]);
    let minutes = parseInt(cont_time[1]);
    let seconds = parseInt(cont_time[2]);
  
    seconds += secondsToAdd;
    if (seconds >= 60) {
      minutes += Math.floor(seconds / 60);
      seconds %= 60;
    }
    if (minutes >= 60) {
      hours += Math.floor(minutes / 60);
      minutes %= 60;
    }
  
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');
  
    return `${hours}:${minutes}:${seconds}`;
  }

function checkGameStatus() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/check_game_status.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(params);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        const response = xhr.responseText;
        const responseData = JSON.parse(response);
        const gameStatus = responseData.game_status;
        const userStatus = responseData.user_status;
        start_time = responseData.start_time;
        if (userStatus === '0'){
            kick_player(true);
            return;
        }
        if (gameStatus === '1') {
            let now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let now_time = `${hours}:${minutes}:${seconds}`;
            let date1 = new Date('1970-01-01T' + start_time + 'Z');
            let date2 = new Date('1970-01-01T' + now_time + 'Z');
            
            const diff_seconds = (date2.getTime() - date1.getTime()) / 1000;
            const num_of_50_sec_intervals = Math.floor(diff_seconds / 50);
            round = num_of_50_sec_intervals;
            if (round > 1){
                final_stage();
                return
            }
            start_time = addSecondsToTime(start_time, 50 * round);
            
            first_stage();
        } else {
            setTimeout(checkGameStatus, 1000);
        }
    }};
}

function first_stage(){
    $('.round').html(`${round + 1}`);
    updateImages();
    updateTheme(round);
    waitBlock.style.display = "none";
    let mainBlock = document.getElementById("main");
    mainBlock.style.display = "flex";
    themeBlock.style.display = "flex";
    imageBlock.style.display = "flex";
    timerBlock.style.display = 'flex';
    let saved_img_display_value = localStorage.getItem("image_block_display");
    if (saved_img_display_value) {
        imageBlock.style.display = saved_img_display_value;
    }

    let timer_func = setInterval(function() {
        let answer = updateTimer(30, start_time);
        if (answer <= 0) {
            clearInterval(timer_func);

            imageBlock.style.display = "none";
            localStorage.setItem("image_block_display", imageBlock.style.display);
            resultBlock.style.display = "flex";
            
            second_stage();
            return;
        }}, 500);
}

function second_stage(){
    let saved_img_display_value = localStorage.getItem("image_block_display");
    if (saved_img_display_value) {
        imageBlock.style.display = saved_img_display_value;
    }

    start_time = addSecondsToTime(start_time, 30);
    let timer_func = setInterval(function() {
        let answer = updateTimer(20, start_time);
        if (answer <= 0) {
            clearInterval(timer_func);
            resultBlock.style.display = "none";
            localStorage.setItem("result_block_display", resultBlock.style.display);

            third_stage();
            return;
        }}, 500);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/get_image_list.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(params);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const images = JSON.parse(xhr.responseText);
            const imageButtons = document.querySelectorAll('.vote_image');
            const imageIndexes = Object.keys(images).sort((a, b) => a - b); 
            shuffle(imageIndexes);
            for (let i = 0; i < imageIndexes.length; i++) {
                const index = imageIndexes[i];
                const image = images[index].toString();
                const imgElement = imageButtons[i].querySelector('img');
                imgElement.src = `../images/image${image}.png`;
            }
            let result_block_display_value = localStorage.getItem("result_block_display");
            if (result_block_display_value) {
                resultBlock.style.display = result_block_display_value;
            } else {
                resultBlock.style.display = 'flex';
            }
        };
    }
};

function third_stage(){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/clear_images.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'room_id=' + encodeURIComponent(room_id);
    xhr.send(data);

    imageBlock.style.display = "flex";
    localStorage.setItem("image_block_display", imageBlock.style.display);
    localStorage.setItem("result_block_display", "flex");
    round = round + 1;
    imageBlock.style.display = "flex";
    check_round(round);
};

function final_stage(){
    let points;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/get_points.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'room_id=' + encodeURIComponent(room_id);
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            var response = JSON.parse(this.responseText);
            points = response;
        }
    };
    xhr.send(data);
    
    localStorage.clear();

    waitBlock.style.display = "none";
    imageBlock.style.display = "none";
    timerBlock.style.display = 'none';
    resultBlock.style.display = 'none';
    themeBlock.style.display = "none";

    preFinalBlock.style.display = 'flex';
    setTimeout(function() {
        preFinalBlock.style.display = 'none';

        console.log(points);
        let pointsArr = Object.entries(points);
        pointsArr.sort((a, b) => b[1] - a[1]);
        for (let i = 0; i < pointsArr.length; i++) {
            pointsArr[i][1] = parseInt(pointsArr[i][1]);
        }
        let place = 0;
        let lastScore = null;
        pointsArr.forEach((player, index) => {
            if (player[1] !== lastScore) {
                place = index + 1;
            }

            lastScore = player[1];
            player.push(place);
        });
        let places = document.getElementById('places');

        for (let i = 0; i < pointsArr.length; i++) {
        let place = document.createElement('div');
        let rank = document.createElement('div');
        let num = document.createElement('div');
        let player_bg = document.createElement('div');
        let player = document.createElement('div');
        let points = document.createElement('div');
        let point = document.createElement('div');

        if (pointsArr[i][2] == 1) {
            rank.classList.add('gold');
            player_bg.classList.add('gold_bg');
        } else if (pointsArr[i][2] == 2) {
            rank.classList.add('silver');
            player_bg.classList.add('silver_bg');
        } else if (pointsArr[i][2] == 3) {
            rank.classList.add('bronze');
            player_bg.classList.add('bronze_bg');
        } else {
            rank.classList.add('default');
            player_bg.classList.add('default_bg');
        }
        num.classList.add('num');
        place.classList.add('place');
        player.classList.add('username');
        points.classList.add('points_bg');
        point.classList.add('point');

        player.textContent = pointsArr[i][0];
        point.textContent = pointsArr[i][1];
        num.textContent = pointsArr[i][2];

        rank.appendChild(num);
        place.appendChild(rank);

        player_bg.appendChild(player);
        place.appendChild(player_bg);

        points.appendChild(point);
        place.appendChild(points);

        places.appendChild(place);
        }
        console.log(pointsArr);
        finalBlock.style.display = 'flex';
    }, 3000);
    

    setTimeout(function() {
        kick_player();
    }, 3000);
    return;
}

function check_round(round){
    if (round >= 1){
        final_stage();
        return;
    }
    else {
        start_time = addSecondsToTime(start_time, 20);
        first_stage();
        return;
    } 
}
const imageButtons = document.querySelectorAll('.image_button');
const voteButtons = document.querySelectorAll('.vote_image');
const themeBlock = document.getElementById("theme");
const imageBlock = document.getElementById('image_block');
const resultBlock = document.getElementById('result_block');
const timerBlock = document.getElementById('timer_block');
const waitBlock = document.getElementById('game_waiting');
const preFinalBlock = document.getElementById('pre_final_block');
const finalBlock = document.getElementById('final_block');

const timer = document.getElementById("timer");

imageButtons.forEach(button => {
    button.addEventListener('click', event => {
        const buttonElement = event.currentTarget;
        const imageElement = buttonElement.querySelector('img');
        const imageSrc = imageElement.getAttribute('src');
        const imageName = imageSrc.split('/').pop();
        let imageNumber = imageName.match(/\d+/)[0];
        
        //Обновит список картинок после нажатия кнопки
        let image_list = JSON.parse(localStorage.getItem('images_list'));
        intImageNum = parseInt(imageNumber);
        randomNum = Math.floor(Math.random() * 24) + 1;
        while (image_list.indexOf(randomNum) !== -1) {
            randomNum = Math.floor(Math.random() * 24) + 1;
        }
        index = image_list.indexOf(intImageNum);
        image_list.splice(index, 1, randomNum);
        localStorage.setItem("images_list", JSON.stringify(image_list));
         
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/push_image.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
        xhr.send(data);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let result = xhr.responseText;
                if (result == 'false'){
                    // Тут надо реализовать какой то код который будет выдавать норм ошибку
                    alert("Таку картинку вже обрали");
                }
                else{
                    imageBlock.style.display = "none";
                    localStorage.setItem("image_block_display", imageBlock.style.display);
                }
            };
        }     
    });
});

voteButtons.forEach(button => {
    button.addEventListener('click', event => {
        const buttonElement = event.currentTarget;
        const imageElement = buttonElement.querySelector('img');
        const imageSrc = imageElement.getAttribute('src');
        const imageName = imageSrc.split('/').pop();
        let imageNumber = imageName.match(/\d+/)[0];
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/push_points.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
        xhr.send(data);
        
        resultBlock.style.display = "none";
        localStorage.setItem("result_block_display", resultBlock.style.display);
    });
});

checkGameStatus();