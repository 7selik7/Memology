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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
  }, 1000);
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
            <div class="username"></div>
          </div>
          <button class='cross-btn' data-id="1"></button>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username"></div>
          </div>
          <button class='cross-btn' data-id="2"></button>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username"></div>
          </div>
          <button class='cross-btn' data-id="3"></button>
        </div>

        <div class="user">
          <div class="userbg">
            <div class="username"></div>
          </div>
          <button class='cross-btn' data-id="4"></button>
        </div>
      </div>  
        
      <div class="buttons">
        <div class="border">
          <button id='invite'>
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
        var buttons = document.querySelectorAll('.username');
        var hasText = true;
        buttons.forEach(function(button) {
          if (!button.innerText) {
            hasText = false;
          }
        });
        if (hasText) {
          var xhr = new XMLHttpRequest();
          xhr.open('POST', '../includes/update_game_status.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          var data = "game_status=1&room_id=<?php echo '$room_id'?>";
          xhr.send(data);
          window.location.href = '../games/game_$room_id.php';
        } else {
          Toastify({
          text: "Не всі під`єдналися",
          style: {
            background: 'rgba(240, 68, 56, 1)',
            borderRadius: '3vh' 
          },
          duration: 2000,
          position: 'center',
        }).showToast();
        }
      });

      let kickButtons = document.querySelectorAll('.cross-btn');
      kickButtons.forEach(button => {
        button.addEventListener('click', event => {
          if (button.dataset.id == 1){
            var sound = new Audio('../source/kto_kuda.mp3');
            sound.play();
            setTimeout(function(){
              alert("ТЫ КУДА СОБРАЛСЯ?");
            }, 1500);
          }
          else{
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../includes/delete_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            var data = "room_id=<?php echo '$room_id'?>&index=" + button.dataset.id;
            xhr.send(data);
          }
        });
      });

      let inviteButton = document.getElementById('invite');
      inviteButton.addEventListener('click', function() {
        let link = 'localhost/Memology';
        navigator.clipboard.writeText(link);
        Toastify({
          text: 'Посилання скопійовано в буфер обміну',
          style: {
            background: 'rgba(240, 68, 56, 1)',
            borderRadius: '3vh' 
          },
          duration: 2000,
          position: 'center',
        }).showToast();
      });

    </script>
  </div>
</body>
</html>
