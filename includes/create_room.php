<?php
//Подключение к базе данных
include('../includes/connect_db.php');

//Получаем данные с формы после нажати кнопки "создать комнату"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получаем значения, отправленные через метод POST
  $code = $_POST['code'];
  $password = $_POST['password'];
  $name = $_POST['name'];
}

//Создаем запрос sql для проверки имени
$sql = "SELECT * FROM rooms WHERE room_name='$code'";
$result = mysqli_query($connection, $sql);

// Проверяем количество строк в результате
if (mysqli_num_rows($result) > 0) {
  echo "Така кімната вже існує";
//Если комнаты с таким именем нету, то заносим внесенные данные в бд
} else {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $room_id = '';// вставить проверку есть ли такой айди в базе
    for ($i = 0; $i < 4; $i++) {
      $room_id .= $chars[rand(0, strlen($chars) - 1)];
    }

    $sql = "INSERT INTO `rooms` (`id`, `room_name`, `room_password`, `room_id`, `user1`, `game_status`) 
    VALUES (NULL, '$code', '$password', '$room_id', '$name', False);";
    if (mysqli_query($connection, $sql)) {
      //Уведомляем об успешном внесений в бд
      echo "New record created successfully";
    } else {
      //Выводим ошибку если не удасться занести данные в бд
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    //Создаем файл комнаты (лобби) и файл игры (где будес сам процес игры написан) 
    $filename = "../games/game_" . $room_id . ".php";
    $template_path = "../templates/game_template.php";
    $content = file_get_contents($template_path);
    $content = str_replace('$code', $code, $content);
    file_put_contents($filename, $content);

    $filename = "../rooms/" . $room_id . ".php";
    $template_path = "../templates/template.php";
    $content = file_get_contents($template_path);
    $content = str_replace('$code', $code, $content);
    $content = str_replace('$room_id', $room_id, $content);
    file_put_contents($filename, $content);
    header('Location: ' . $filename);

    $connection->close();
    exit;
}

?>
