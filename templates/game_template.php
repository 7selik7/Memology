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
<?php 
  $indexes = array();
  $images = array();
  if (!(empty($_SESSION['user_image_indexes']))) {
    $indexes = $_SESSION['user_image_indexes'];// Если такая переменная сущесвтует то ничего с ней не делаем.
  } else { // Если такой переменной нету то присваеваем ей 6 рандомных индексов.  
    while (count($indexes) < 6) {
      $random_index = mt_rand(1, 24);
      if (!in_array($random_index, $indexes)){$indexes[] = $random_index;}
    } 
    $_SESSION['user_image_indexes'] = $indexes;
  }
  for ($i = 0; $i < 6; $i++) {
    $image_path = sprintf('../images/image%d.jpg', $indexes[$i]);
    $images[] = $image_path;
  }
  ?>



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
              <?php 
                for ($i = 0; $i < 3; $i++){
                  echo "<button class='image_button'><img src='$images[$i]' width='300' height='180'></button>";
                }?>     
          </div>
          <div class="rows">
            <?php 
              for ($i = 3; $i < 6; $i++){
                echo "<button class='image_button'><img src='$images[$i]' width='300' height='180'></button>";
              }?>
          </div>
      </div>
      <div id="result_block">
        <h1 class="theme"></h1>
      </div>

      <div id="timer_block"> <h1 id='timer'></h1> </div>
  </div>

<script>
  let start_time = '';
  let room_id = '$room_id';
  const params = "room_id=$room_id";
</script>

<script src="../includes/game.js"></script>
<script src="../includes/timer.js"></script>

</body>
</html>