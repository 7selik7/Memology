<?php

include('../includes/connect_db.php');


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

    $sql = "SELECT room_id FROM `rooms` WHERE room_name = '$room_name'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $room_id = $row["room_id"];
    $filename = "../games/game_" . $room_id . ".php";
    header('Location: ' . $filename);
    exit();


    
  } else {
      header("Location: ../index.html");
      exit();
  }
} else {
  header("Location: ../index.html");
  exit();
}

$connection->close();
?>



