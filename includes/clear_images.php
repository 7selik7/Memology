<?php
include('connect_db.php'); 

$room_id = $_POST['room_id'];
$sql = "UPDATE `games` SET `image1` = NULL, `image2` = NULL, `image3` = NULL, `image4` = NULL WHERE `room_id` = '$room_id'";


mysqli_query($connection, $sql);
$connection->close();
?>