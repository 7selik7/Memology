function updateTheme(){
    $.ajax({
        url: '../includes/get_theme.php',
        data: {room_id: room_id},
        success: function(response){
          $('.theme').html(response); 
        }
    });
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
        start_time = responseData.start_time;

        if (gameStatus === '1') {
            updateTheme();
            waitBlock.style.display = "none";
            imageBlock.style.display = "flex";
            timerBlock.style.display = 'flex';
            first_stage();
        } else {
            setTimeout(checkGameStatus, 1000);
        }
    }};
}

function first_stage(){
    let saved_img_display_value = localStorage.getItem("image_block_display");
    let result_block_display_value = localStorage.getItem("result_block_display");
    if (saved_img_display_value) {
        imageBlock.style.display = saved_img_display_value;
    }
    if (result_block_display_value) {
        resultBlock.style.display = result_block_display_value;
    }

    imageButtons.forEach(button => {
        button.addEventListener('click', event => {
            const buttonElement = event.currentTarget;
            const imageElement = buttonElement.querySelector('img');
            const imageSrc = imageElement.getAttribute('src');
            const imageName = imageSrc.split('/').pop();
            let imageNumber = imageName.match(/\d+/)[0];

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../includes/push_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
            xhr.send(data);

            imageBlock.style.display = "none";
            localStorage.setItem("image_block_display", imageBlock.style.display);
            
        });
    });

    let timer_func = setInterval(function() {
        let answer = updateTimer(30, start_time);
        if (answer <= 0) {
            clearInterval(timer_func);

            imageBlock.style.display = "none";
            localStorage.setItem("image_block_display", imageBlock.style.display);
            resultBlock.style.display = "flex";
            localStorage.setItem("result_block_display", resultBlock.style.display);
            
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
            const buttons = [];
            const resultBlock = document.getElementById('result_block');
            for (let i = 0; i < 4; i++) {
                if (!images.hasOwnProperty(i)) {
                    continue;
                }
                const imgSrc = `../images/image${images[i]}.jpg`;
                const btn = document.createElement('button');
                btn.className = 'vote_image';
                btn.innerHTML = `<img src="${imgSrc}" width="300" height="180">`;
                buttons.push(btn);
            }
            buttons.sort(() => Math.random() - 0.5);
            for (let i = 0; i < 3; i++) {
                resultBlock.appendChild(buttons[i]);
            }
            let result_block_display_value = localStorage.getItem("result_block_display");
            if (result_block_display_value) {
                resultBlock.style.display = result_block_display_value;
            } else {
                resultBlock.style.display = 'flex';
            }
        };
    }

    const resultBlock = document.getElementById('result_block');
    resultBlock.addEventListener('click', event => {
    const buttonElement = event.target.closest('.vote_image');
    if (buttonElement) {
        const imageElement = buttonElement.querySelector('img');
        const imageSrc = imageElement.getAttribute('src');
        const imageName = imageSrc.split('/').pop();
        let imageNumber = imageName.match(/\d+/)[0];
        console.log(imageNumber);
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/push_points.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
        xhr.send(data);
        
        resultBlock.style.display = "none";
        localStorage.setItem("result_block_display", resultBlock.style.display);
        
    
    }
    });
};


function third_stage(){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/clear_images.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'room_id=' + encodeURIComponent(room_id);
    xhr.send(data);

    imageBlock.style.display = "flex";
    localStorage.setItem("image_block_display", imageBlock.style.display);
    i = i + 1;
    imageBlock.style.display = "flex";

    const resultBlock = document.getElementById('result_block');
    const voteImages = resultBlock.querySelectorAll('.vote_image');

    for (let i = 0; i < voteImages.length; i++) {
        resultBlock.removeChild(voteImages[i]);
    }

    first_stage();
    start_time = addSecondsToTime(start_time, 20);
};

function final_stage(){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../includes/clear_session.php');
    xhr.send();
    localStorage.clear();

    waitBlock.style.display = "none";
    imageBlock.style.display = "none";
    timerBlock.style.display = 'none';
    resultBlock.style.display = 'none';

    finalBlock.style.display = 'flex';
    return;
}

function check_round(){
    if (i >= 3){
        final_stage();
    }
    else {
        start_time = addSecondsToTime(start_time, 50 * i);
        checkGameStatus();
    }
    return;
}

const imageButtons = document.querySelectorAll('.image_button');
const imageBlock = document.getElementById('image_block');
const resultBlock = document.getElementById('result_block');
const timerBlock = document.getElementById('timer_block');
const waitBlock = document.getElementById('game_waiting');
const finalBlock = document.getElementById('final_block');
const timer = document.getElementById("timer");

let now = new Date();
let hours = now.getHours().toString().padStart(2, '0');
let minutes = now.getMinutes().toString().padStart(2, '0');
let seconds = now.getSeconds().toString().padStart(2, '0');
let now_time = `${hours}:${minutes}:${seconds}`;
let date1 = new Date('1970-01-01T' + start_time + 'Z');
let date2 = new Date('1970-01-01T' + now_time + 'Z');

const diff_seconds = (date2.getTime() - date1.getTime()) / 1000;
const num_of_50_sec_intervals = Math.floor(diff_seconds / 50);
i = num_of_50_sec_intervals;
check_round();
