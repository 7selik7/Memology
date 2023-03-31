// получить все кнопки с классом "image-button"
const imageButtons = document.querySelectorAll('.image_button');

// добавить обработчик событий для каждой кнопки
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

         
    });
});


