<!-- Наступний скрипт перевіряє чи ввів користувач пароль чи просто вів адресу -->
<?php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: ../index.php');
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style_game.css">
    <title>Document</title>
</head>
<?php 
  $images = array();
  for ($i = 1; $i <= 24; $i++) {
    $image_path = sprintf('../images/image%d.jpg', $i);
    $images[] = $image_path;
  }?>
<!-- Этот блок высветиться игрокам которые зайдут в комнату и будут ждать пока админ (тот кто создал комнату) запустит игру. Тоесть тут просто 
можно сделать просто надпись на фоне "ожидайте начало игры, также можно добавить какой интенрактив типо крутящегося колечка например"-->
<div id='game_waiting'>
  <h1>Очікуйте коли гра почнеться</h1>
</div>
<!-- Дальше идет блок body этот блок появиться у всех пользователей и у админа в том числе, тоесть тут уже должен быть отрисован сам процес игры--> 
<body>
    <div id="alona">
        <div id="image_block">
            <div class="rows">
                <?php 
                  for ($i = 0; $i < 3; $i++){
                    shuffle($images);
                    echo "<button class='image-button'><img src='$images[0]' width='300' height='180'></button>";
                    unset($images[0]);
                  }?>
            </div>
            <div class="rows">
              <?php 
                for ($i = 0; $i < 3; $i++){
                  shuffle($images);
                  echo "<button class='image-button'><img src='$images[0]' width='300' height='180'></button>";
                  unset($images[0]);
               }?>
            </div>
        </div>
        <div id="timer_block">
            <h1 id='timer'></h1>
        </div>
    </div>
<script>
  let start_time = '';
  let room_id = '$room_id';
  function checkGameStatus() {
    const xhr = new XMLHttpRequest();

    xhr.open("POST", "../includes/check_game_status.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    const params = "room_id=$room_id";
    xhr.send(params);


    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        const response = xhr.responseText;

        const responseData = JSON.parse(response);
        const gameStatus = responseData.game_status;
        start_time = responseData.start_time;

        if (gameStatus === '1') {
          document.getElementById("alona").style.display = "flex";
          document.getElementById("game_waiting").style.display = "none";
          return;
        } else {
          setTimeout(checkGameStatus, 1000);
        }
      }
    };
  }
  checkGameStatus();
</script>
<script src="../includes/timer.js"></script>
</body>
</html>