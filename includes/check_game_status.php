<?php

include('../includes/connect_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];

    // Выборка значения game_status из таблицы rooms
    $sql = "SELECT `game_status` FROM `rooms` WHERE `room_id`='$room_id'";
    $result1 = $connection->query($sql);
    $row1 = $result1->fetch_assoc();

    $sql = "SELECT `start_time` FROM `games` WHERE `room_id`='$room_id'";
    $result2 = $connection->query($sql);
    $row2 = $result2->fetch_assoc();

    // Формирование ответа в виде ассоциативного массива
    $response = array(
        'game_status' => $row1['game_status'],
        'start_time' => $row2['start_time']
    );
    $json_response = json_encode($response);

    header('Content-Type: application/json');
    echo $json_response;
}
?>



