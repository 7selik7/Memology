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
  <title>Document</title>
  <link rel="stylesheet" href="../styles/style_menu.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function(){
  setInterval(function(){
      $.ajax({
           url: '../includes/get_user_list.php?room_id=<?php echo '$room_id'; ?>',
           success: function(response){
             $('.username').each(function(index) {
               $(this).text(response[index]);
             });
         }
    });
  }, 2000);
  }); 
</script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
</style>

<body>
  <div class="room">
    <label class="room_name" for="">
      <?php echo '$room_name'; ?>
    </label>

    <div class="frame1">
      <div class="users">
        <div class="user">
          <div class="userbg">
            <div class="username" for="">
            </div>
          </div>
          <img alt="cross" class="cross" src="../images/project_images/cross.svg"/>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username" for="">
            </div>
          </div>
          <img alt="cross" class="cross" src="../images/project_images/cross.svg"/>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username" for="">
            </div>
          </div>
          <img alt="cross" class="cross" src="../images/project_images/cross.svg"/>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username" for="">
            </div>
          </div>
          <img alt="cross" class="cross" src="../images/project_images/cross.svg"/>
        </div>
      </div>  
        
      <div class="buttons">
        <div class="border">
          <button>
            ЗАПРОСИТИ
          </button>
        </div>
        <div class="border">
          <button id='start_game_button'>
            ЗАПУСТИТИ ГРУ
          </button>
        </div>
      </div>
    </div>  

    <script>
      document.getElementById('start_game_button').addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/update_game_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = "game_status=1&room_id=<?php echo '$room_id'?>";
        xhr.send(data);
        window.location.href = '../games/game_$room_id.php';
      });
    </script>
  </div>
</body>
</html>
