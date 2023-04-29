<?php

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $room_name = $_POST['room_name'];
  $password = $_POST['password'];
  $nickname = $_POST['nickname'];
}

// Экранируем специальные символы в значениях параметров
$room_name = mysqli_real_escape_string($connection, $room_name);
$password = mysqli_real_escape_string($connection, $password);

// Формируем SQL-запрос на выборку комнаты по идентификатору и паролю
$sql = "SELECT * FROM `rooms` WHERE room_name='$room_name'";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
  // Получаем данные комнаты из результата запроса
  $room = $result->fetch_assoc();

  // Проверяем совпадение пароля
  if ($room['room_password'] == $password) {
    $sql = "SELECT `user1`, `user2`, `user3`, `user4` FROM `rooms` WHERE `room_name` = '$room_name'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['user1'] !== null && $row['user2'] !== null && $row['user3'] !== null && $row['user4'] !== null) {
      echo 'Місць немає';
      exit;
    }
    //if (!is_null($_SESSION['user_num'])) {
    //  echo 'Ви вже зарегістровані';
    //} 
    session_start();
    $_SESSION['authenticated'] = true;
    $_SESSION['nickname'] = $nickname;
    $sql = "UPDATE `rooms` SET 
      user4 = CASE 
          WHEN user2 IS NOT NULL AND user3 IS NOT NULL AND user4 IS NULL THEN '$nickname' 
          ELSE user4 
      END,
      user3 = CASE 
          WHEN user2 IS NOT NULL AND user3 IS NULL THEN '$nickname' 
          ELSE user3 
      END,
      user2 = CASE 
          WHEN user2 IS NULL THEN '$nickname'ELSE user2 
      END
    WHERE `room_name` = '$room_name'";
    mysqli_query($connection, $sql);

    //Цей запрос потрібен для того щоб визначити, який номер присвоється користувачю який додається до кімнати
    $sql = "SELECT 
              CASE 
                WHEN user4 = '$nickname' THEN 4
                WHEN user3 = '$nickname' THEN 3
                WHEN user2 = '$nickname' THEN 2
              END AS user_num
              FROM `rooms` 
              WHERE `room_name` = '$room_name'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_num'] = $row['user_num'];

    //Цей скрипт отримує room_id для того, щоб запустити сторінку гри
    $sql = "SELECT room_id FROM `rooms` WHERE room_name = '$room_name'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $room_id = $row["room_id"];
    $filename = "../games/game_" . $room_id . ".php";
    echo $filename;
    exit();


    
  } else {
      echo 'Ви вели не вірний пароль';
      exit();
  }
} else {
  echo 'Такої кімнати не існує';
  exit();
}

$connection->close();
?>



