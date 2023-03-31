<?php
include('../includes/connect_db.php');

// Обработка запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $room_id = $_POST["room_id"];
  $image_id = $_POST['image_id'];
  echo $room_id;
  echo $image_id;
  $sql = "UPDATE `games` SET 
      image4 = CASE 
          WHEN image1 IS NOT NULL AND image2 IS NOT NULL AND image3 IS NOT NULL AND image4 IS NULL THEN '$image_id' 
          ELSE image4 
      END,
      image3 = CASE 
          WHEN image1 IS NOT NULL AND image2 IS NOT NULL AND image3 IS NULL THEN '$image_id' 
          ELSE image3 
      END,
      image2 = CASE 
          WHEN image1 IS NOT NULL AND image2 IS NULL THEN '$image_id' ELSE image2 
      END,
      image1 = CASE 
          WHEN image1 IS NULL THEN '$image_id' ELSE image1 
      END
    WHERE `room_id` = '$room_id'";
    mysqli_query($connection, $sql);
}
?>