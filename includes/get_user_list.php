<?php
include('connect_db.php'); 
$room_id = $_GET['room_id'];

// SQL запрос с условием WHERE по полю room
$sql = "SELECT `user1`, `user2`, `user3`, `user4` FROM `rooms` WHERE `room_id` = '$room_id'";
$result = mysqli_query($connection, $sql);

// Формирование списка пользователей
$row = mysqli_fetch_assoc($result);
$user_list = array($row['user1'], $row['user2'], $row['user3'], $row['user4']);
$json = json_encode($user_list);

// отправка ответа клиенту
header('Content-Type: application/json');
echo $json;
$connection->close();
?>
