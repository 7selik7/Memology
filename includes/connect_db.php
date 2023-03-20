<?php
$connection = mysqli_connect('127.0.0.1', 'root', '', 'rooms');

if( $connection == false){
  echo 'Не удалось подкючиться к базе даных';
  exit();
  };

?>
