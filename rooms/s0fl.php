<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function(){
  setInterval(function(){
      $.ajax({
           url: '../includes/get_user_list.php?room_id=<?php echo 's0fl'; ?>',
           success: function(response){
             $('#userList').html(response); 
         }
    });
  }, 2000);
  }); 
  </script>
</head>
<body>
  <div>
    <div>
      <label for="">
          <?php echo 'room7'; ?>
      </label>
    </div>
    <div id="userList">
   
  </div>
  </div>
  <div>
    <button>Запросити</button>
    <button id='start_game_button'>Заупстити гру</button>
  </div>
  <script>
    document.getElementById('start_game_button').addEventListener('click', function() {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../includes/update_game_status.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      var data = "game_status=1&room_id=<?php echo 's0fl'?>";
      xhr.send(data);
      window.location.href = '../games/game_s0fl.php';

  });
  </script>
</body>
</html>