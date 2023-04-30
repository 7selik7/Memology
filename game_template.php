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

<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap');
</style>

<body>
  <!-- Этот блок высветиться игрокам которые зайдут в комнату и будут ждать пока админ (тот кто создал комнату) запустит игру. Тоесть тут просто 
  можно сделать просто надпись на фоне "ожидайте начало игры, также можно добавить какой интенрактив типо крутящегося колечка например"-->
  <div id='game_waiting'>
    <img class="text" src="../images/project_images/waiting_text.svg"/>
  </div>
    <!-- Дальше идет блок body этот блок появиться у всех пользователей и у админа в том числе, тоесть тут уже должен быть отрисован сам процес игры--> 
  <div id="main">
    <div id="theme">
      <div class="frame3">
        <div id="round_block"><h1 class='round'>1</h1></div>
        <div class="round_text"></div>
      </div>
      <div id="theme_block"> 
          <h1 class="theme"></h1>
      </div>
      <div class="frame2">
        <div id="timer_block"> <h1 id='timer'></h1></div>
        <div class="timer_text"></div>
      </div>
    </div>
    <div id="image_block">
      <div class="memes">
        <div class="rows">
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto;'></button>
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto;'></button>  
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto;'></button>    
        </div>
        <div class="rows">
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto'></button>
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto'></button>  
          <button class='image_button'><img src='' style='width: 35.5vh; height: auto'></button>  
        </div>
      </div>
    </div>
    <div id="result_block">
      <div class="vote_text"></div>
      <div class="rows_vote"><!-- свой класс придумай -->
        <button class='vote_image'><img src='' style='width: 35.5vh; height: auto;'></button>
        <button class='vote_image'><img src='' style='width: 35.5vh; height: auto;'></button>  
        <button class='vote_image'><img src='' style='width: 35.5vh; height: auto;'></button>  
      </div>
    </div>

    <div id="pre_final_block"> 
      <div class="game_over"></div>      
    </div>

    <div id="final_block">
            <img class="cup" src="../images/project_images/cup.svg"/>
            <div class="results_bg">
                <img class="results_text" src="../images/project_images/results_text.svg"/>
                <div class="rating">
                    <div class="place">
                        <div class="gold"><div class="num">1</div></div>
                        <div class="gold_bg">
                            <div class="username">Альона</div>
                        </div>
                        <div class="points_bg"><div class="point">9</div></div>
                    </div> 
                    <div class="place">
                        <div class="silver"><div class="num">2</div></div>
                        <div class="silver_bg">
                            <div class="username">Женялох</div>
                        </div>
                        <div class="points_bg"><div class="point">6</div></div>
                    </div>
                    <div class="place">
                        <div class="bronze"><div class="num">3</div></div>
                        <div class="bronze_bg">
                            <div class="username">Вітя</div>
                        </div>
                        <div class="points_bg"><div class="point">3</div></div>
                    </div><div class="place">
                        <div class="default"><div class="num">4</div></div>
                        <div class="default_bg">
                            <div class="username">Степан</div>
                        </div>
                        <div class="points_bg"><div class="point">1</div></div>
                    </div>
                </div>
            </div>
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