<?php
include('connect_db.php'); 

$room_id = $_POST['room_id'];
$sql = "UPDATE `games` SET `image1` = 0, `image2` = 0, `image3` = 0, `image4` = 0 WHERE `room_id` = '$room_id'";


mysqli_query($connection, $sql);
$connection->close();
?>