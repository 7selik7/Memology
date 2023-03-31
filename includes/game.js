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
            document.getElementById("game_waiting").style.display = "none";
            document.getElementById("main").style.display = "flex";
            // Запуск первого этапа
            first_stage();
        } else {
            setTimeout(checkGameStatus, 1000);
        }
    }};
}
// удалить картинку со списка
function first_stage(){
    let timer_func = setInterval(function() {
        let answer = updateTimer(60, start_time);
        if (answer <= 0) {
            clearInterval(timer_func);
            second_stage();
        }}, 500);

    
    let saved_img_display_value = localStorage.getItem("image_block_display");
    if (saved_img_display_value) {
        imageBlock.style.display = saved_img_display_value;
    }

    imageButtons.forEach(button => {
        button.addEventListener('click', event => {
            const buttonElement = event.currentTarget;
            const imageElement = buttonElement.querySelector('img');
            const imageSrc = imageElement.getAttribute('src');
            const imageName = imageSrc.split('/').pop();
            let imageNumber = imageName.match(/\d+/)[0];
            console.log(imageNumber);

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

function second_stage(){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/get_image_list.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(params);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
        const images = JSON.parse(xhr.responseText);
        const resultBlock = document.getElementById('result_block');
        for (let i = 0; i < images.length; i++) {
            const imgSrc = `../images/image${images[i]}.jpg`;
            const btn = document.createElement('button');
            btn.className = 'vote_image';
            btn.innerHTML = `<img src="${imgSrc}" width="300" height="180">`;
            resultBlock.appendChild(btn);
        }}
    };
}

//Вводим все переменные которые нам пригодяться чтобы они были вызваны с самого начала
const imageButtons = document.querySelectorAll('.image_button');
const imageBlock = document.getElementById('image_block');
const timer = document.getElementById("timer");

//Основновй код который все запускает
checkGameStatus();







//Проверка оттправлялись ли картинки на сервер





