<?php
include('connect_db.php'); 
$room_id = $_GET['room_id'];

// SQL запрос с условием WHERE по полю room
$sql = "SELECT * FROM `rooms` WHERE room_id = '$room_id'";
$result = $connection->query($sql);

// Формирование списка пользователей
$userList = "<ul>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $userList .= "<li>".$row["user1"]."</li>";
        $userList .= "<li>".$row["user2"]."</li>";
        $userList .= "<li>".$row["user3"]."</li>";
        $userList .= "<li>".$row["user4"]."</li>";
    }
} 
$userList .= "</ul>";
echo $userList;
$connection->close();
?>
