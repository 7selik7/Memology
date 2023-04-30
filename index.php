<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memology</title>
    <link rel="stylesheet" href="styles/style_main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
</style>

<body>
    <div class="main">
        <img
        alt="MEMOLOGY"
        class="logo"
        src="images/project_images/logo.svg"
        />
        <div class="flex-wrapper-one">
            <form id="create_form" class="create_form" method="post" action="includes/create_room.php">
                <div class="frame2">
                    <input type="text" id="code" name="room_name" placeholder="Назва кімнати">
                    <input type="password" id="password" name="password" placeholder="Пароль">
                    <input type="text" id="name" name="nickname" placeholder="Ім'я">
                </div>
                <button type="submit" class="button-create">СТВОРИТИ</button>
            </form>

            <form id="enter_form" class="create_form" method="post" action="includes/enter_room.php">
                <div class="frame2">
                    <input type="text" class="code" id="code" name="room_name" placeholder="Назва кімнати">
                    <input type="password" class="code" id="password" name="password" placeholder="Пароль">
                    <input type="text" class="code" id="name" name="nickname" placeholder="Ім'я">
                </div>
                <button type="submit" class="button-enter">ВВІЙТИ</button>
            </form>
        </div>
    </div>
</body>
<script>
    localStorage.clear();
    const createForm = document.getElementById("create_form");
    const enterForm = document.getElementById("enter_form");

    createForm.addEventListener("submit", submitForm);
    enterForm.addEventListener("submit", submitForm);

    function submitForm(event) {
        event.preventDefault(); 

        const form = event.target; 
        const formData = new FormData(form); 

        // выполняем запрос AJAX
        const xhr = new XMLHttpRequest();
        xhr.open(form.method, form.action);
        xhr.onload = function () {
            if (xhr.status === 200) {
                ans = xhr.responseText;
                if (ans.startsWith(".")) {
                    ans = ans.substring(3);
                    window.location.href = ans;
                } else {
                    Toastify({
                    text: ans,
                    style: {
                        background: 'rgba(240, 68, 56, 1)',
                        borderRadius: '1.5vh' 
                    },
                    duration: 2000,
                    position: 'center',
                    }).showToast();
                }
            }
        }
           
        xhr.send(formData);
        
    }
</script>
</html>
