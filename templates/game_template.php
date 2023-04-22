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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>

<body>
  <!-- Этот блок высветиться игрокам которые зайдут в комнату и будут ждать пока админ (тот кто создал комнату) запустит игру. Тоесть тут просто 
  можно сделать просто надпись на фоне "ожидайте начало игры, также можно добавить какой интенрактив типо крутящегося колечка например"-->
  <div id='game_waiting'>
    <h1>Очікуйте коли гра почнеться</h1>
  </div>
    <!-- Дальше идет блок body этот блок появиться у всех пользователей и у админа в том числе, тоесть тут уже должен быть отрисован сам процес игры--> 
  <div id="main">
      <div id="image_block">
          <div id="theme_block"> 
            <h1 class="theme"></h1>
          </div>
          <div class="rows">
            <button class='image_button'><img src='' width='300' height='180'></button>
            <button class='image_button'><img src='' width='300' height='180'></button>  
            <button class='image_button'><img src='' width='300' height='180'></button>    
          </div>
          <div class="rows">
            <button class='image_button'><img src='' width='300' height='180'></button>
            <button class='image_button'><img src='' width='300' height='180'></button>  
            <button class='image_button'><img src='' width='300' height='180'></button>  
          </div>
      </div>
      <div id="result_block">
          <div id="theme_block"> 
            <h1 class="theme"></h1>
          </div>
          <div class="rows"><!-- свой класс придумай -->
            <button class='vote_image'><img src='' width='300' height='180'></button>
            <button class='vote_image'><img src='' width='300' height='180'></button>  
            <button class='vote_image'><img src='' width='300' height='180'></button>  
          </div>
      </div>
      <div id="timer_block"> <h1 id='timer'></h1> </div>

      <div id="final_block"> 
        <h1>Гру завершено!!!</h1>      
      </div>

  </div>

<script>
  let start_time = '';
  let round = 0;
  let room_id = '$room_id';
  const params = "room_id=$room_id";
</script>

<script src="../includes/game.js"></script>
<script src="../includes/timer.js"></script>

</body>
</html>