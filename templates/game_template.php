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
                    echo '<button><img src="' . $images[0] . '" width="300" height="180"></button>';
                    unset($images[0]);
                  }?>
            </div>
            <div class="rows">
              <?php 
                for ($i = 0; $i < 3; $i++){
                  shuffle($images);
                  echo '<button><img src="' . $images[0] . '" width="300" height="180"></button>';
                  unset($images[0]);
               }?>
            </div>
        </div>
    </div>
</body>
</html>