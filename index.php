<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style_main.css">
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

            <form if="enter_form" class="create_form" method="post" action="includes/enter_room.php">
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
</html>
