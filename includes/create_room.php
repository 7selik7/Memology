<?php
//Подключение к базе данных
include('../includes/connect_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $room_name = isset($_POST['room_name']) ? $_POST['room_name'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $nickname = isset($_POST['nickname']) ? $_POST['nickname'] : '';

  if (empty($room_name) || empty($password) || empty($nickname)) {
    echo 'Заповніть всі поля';
    exit;
  }
}

//Получаем данные с формы после нажати кнопки "создать комнату"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получаем значения, отправленные через метод POST
  $room_name = $_POST['room_name'];
  $password = $_POST['password'];
  $nickname = $_POST['nickname'];
}

//Создаем запрос sql для проверки имени
$sql = "SELECT * FROM rooms WHERE room_name='$room_name'";
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
    VALUES (NULL, '$room_name', '$password', '$room_id', '$nickname', False);";
    mysqli_query($connection, $sql);

    $sql = "INSERT INTO `games` (`id`, `room_id`) VALUES (NULL, '$room_id');";
    mysqli_query($connection, $sql);
    
    //Создаем файл комнаты (лобби) и файл игры (где будес сам процес игры написан) 
    $filename = "../games/game_" . $room_id . ".php";
    $template_path = "../templates/game_template.php";
    $content = file_get_contents($template_path);
    $content = str_replace('$room_id', $room_id, $content);
    file_put_contents($filename, $content);

    $filename = "../rooms/" . $room_id . ".php";
    $template_path = "../templates/template.php";
    $content = file_get_contents($template_path);
    $content = str_replace('$room_name', $room_name, $content);
    $content = str_replace('$room_id', $room_id, $content);
    file_put_contents($filename, $content);
    session_start();
    if (isset($_SESSION['current_room'])){
      if ($_SESSION['user_num'] == 1){
        $del_room = $_SESSION['current_room'];
        $file_path = "../rooms/" . $del_room . ".php";
        unlink($file_path);

        $file_path = "../games/game_" . $del_room . ".php";
        unlink($file_path);

        $sql = "DELETE FROM games WHERE room_id='$del_room'";
        mysqli_query($connection, $sql);

        $sql = "DELETE FROM rooms WHERE room_id='$del_room'";
        mysqli_query($connection, $sql);
      }
    }
    session_unset();
    $_SESSION['authenticated'] = true;
    $_SESSION['user_num'] = 1;
    $_SESSION['current_room'] = $room_id;
    $_SESSION['nickname'] = $nickname;

    $connection->close();
    echo $filename;
}

?>
