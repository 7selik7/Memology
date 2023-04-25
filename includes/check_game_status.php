<?php

include('../includes/connect_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    session_start();
    $nickname = $_SESSION['nickname'];
    $user_num = $_SESSION['user_num'];
    $user = 'user' . $user_num;

    // Выборка значения game_status из таблицы rooms
    $sql = "SELECT `game_status`, `$user` FROM `rooms` WHERE `room_id`='$room_id'";
    $result1 = $connection->query($sql);
    $row1 = $result1->fetch_assoc();

    $sql = "SELECT `start_time` FROM `games` WHERE `room_id`='$room_id'";
    $result2 = $connection->query($sql);
    $row2 = $result2->fetch_assoc();

    if ($row1['user' . $user_num] == $nickname){
        $user_status = '1';
    }
    else {
        $user_status = '0';
    }
    
    $response = array(
        'game_status' => $row1['game_status'],
        'start_time' => $row2['start_time'],
        'user_status' => $user_status
    );
    $json_response = json_encode($response);

    header('Content-Type: application/json');
    echo $json_response;
}
?>



