/* 
Функция которая принимает все ответы - 50 секунд 
    поставить слушатели на кнопки при нажатии внести данные в базу данных
        если ты нажал на картинку блок: нан и ждешь таймер и удалить картинку со списка
            когда таймер будет ноль сделать return тем самым запустить следущий скрипт
    сделать таймер на 50 секунд

Функция которая выводит ответы и ждет голосвания - 20 секунд
    получить данные с сервера и создать 4 картинки
        поставить слушатели на кнопки при нажатии на которые на сервер посылаеться запрос с количеством очков
            если проголосовал не убирть картинки, а сделать кнопки disabled
            когда таймер будет ноль сделать return

Функция которая приведет все впорядок и вернет все к начальному состоянию
    очистить поля в базе даных
    вернуть все нужные дисплеи
    удалить со списка картинки которые уже были 

Сам таймер: 
    таймер должен быть в виде функции которая обновляеться через определнный интревал (пол секунды), таймер принимает парамет
    сколько времени ему надо отсчитыть так же ему нужен параметр начального времени которое будет считаться автоматически.

*/
//Функция которя добовляет к начальному времени 
function addSecondsToTime(start_time, secondsToAdd) {
    const cont_time = start_time.split(':'); 
    let hours = parseInt(cont_time[0]);
    let minutes = parseInt(cont_time[1]);
    let seconds = parseInt(cont_time[2]);
  
    // Добавляем секунды
    seconds += secondsToAdd;
  
    // Если добавление секунд приводит к переходу на новую минуту или час,
    // соответствующие значения увеличиваются
    if (seconds >= 60) {
      minutes += Math.floor(seconds / 60);
      seconds %= 60;
    }
    if (minutes >= 60) {
      hours += Math.floor(minutes / 60);
      minutes %= 60;
    }
  
    // Преобразуем значения в строки, добавляя нули спереди при необходимости
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');
  
    // Собираем строку времени в формате "чч:мм:сс" и возвращаем её
    return `${hours}:${minutes}:${seconds}`;
  }

// функция которая проверяет game_status
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
            waitBlock.style.display = "none";
            imageBlock.style.display = "flex";
            timerBlock.style.display = 'flex';
            // Запуск первого этапа
            first_stage();
        } else {
            setTimeout(checkGameStatus, 1000);
        }
    }};
}
// удалить картинку со списка
function first_stage(){
    //Запуск таймера 
    let timer_func = setInterval(function() {
        let answer = updateTimer(30, start_time);
        if (answer <= 0) {
            clearInterval(timer_func);
            second_stage();
        }}, 500);

    //Сохраняет значение чтобы при обновление страницы не было багов 
    let saved_img_display_value = localStorage.getItem("image_block_display");
    if (saved_img_display_value) {
        imageBlock.style.display = saved_img_display_value;
    }

    // Прослухвач на кнопку, після натискання заносить айді кнопки до бази даних
    imageButtons.forEach(button => {
        button.addEventListener('click', event => {
            const buttonElement = event.currentTarget;
            const imageElement = buttonElement.querySelector('img');
            const imageSrc = imageElement.getAttribute('src');
            const imageName = imageSrc.split('/').pop();
            let imageNumber = imageName.match(/\d+/)[0];

            // отправить запрос на добавление картинки в базу данных
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../includes/push_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
            xhr.send(data);

            imageBlock.style.display = "none";

            localStorage.setItem("image_block_display", imageBlock.style.display);
            
        });
    });}

// початок другого етапу
function second_stage(){
    

    // Оновлюється час, до початкового часу додається час після перщого етапу
    let cont_time = addSecondsToTime(start_time, 30);
    let timer_func = setInterval(function() {
        let answer = updateTimer(20, cont_time);
        if (answer <= 0) {
            clearInterval(timer_func);
            third_stage();
        }}, 500);
    // Виводимо кнопки с картинками
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/get_image_list.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(params);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const images = JSON.parse(xhr.responseText);
            const buttons = [];
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

            resultBlock.style.display = 'flex';
            localStorage.setItem("image_block_display", resultBlock.style.display);
            
        };
    }
    let saved_img_display_value = localStorage.getItem("result_block_display");
    if (saved_img_display_value) {
        resultBlock.style.display = saved_img_display_value;
    }
    // Ставимо подію голосування на кнопки
    const resultBlock = document.getElementById('result_block');
    resultBlock.addEventListener('click', event => {
    const buttonElement = event.target.closest('.vote_image');
    if (buttonElement) {
        const imageElement = buttonElement.querySelector('img');
        const imageSrc = imageElement.getAttribute('src');
        const imageName = imageSrc.split('/').pop();
        let imageNumber = imageName.match(/\d+/)[0];
        console.log(imageNumber);
        
        // Отправить запрос на засчитывние очков
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/push_points.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = 'room_id=' + encodeURIComponent(room_id) + '&image_id=' + encodeURIComponent(imageNumber);
        xhr.send(data);
        
        resultBlock.style.display = "none";
        localStorage.setItem("image_block_display", resultBlock.style.display);
        
    
    }
    });
};


// початок третього етапу, який повинен підготувати всі дані для повторення циклу гри 
function third_stage(){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/clear_images.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'room_id=' + encodeURIComponent(room_id);
    xhr.send(data);

    localStorage.removeItem("image_block_display", imageBlock.style.display); 
    localStorage.removeItem("result_block_display", imageBlock.style.display);   

    let cont_time = addSecondsToTime(start_time, 20);
    
    
};





//Вводим все переменные которые нам пригодяться чтобы они были вызваны с самого начала
const imageButtons = document.querySelectorAll('.image_button');



const imageBlock = document.getElementById('image_block');
let resultBlock = document.getElementById('result_block');
const timerBlock = document.getElementById('timer_block');

const waitBlock = document.getElementById('game_waiting');
const timer = document.getElementById("timer");

//Основновй код который все запускает
checkGameStatus();







//Проверка оттправлялись ли картинки на сервер





