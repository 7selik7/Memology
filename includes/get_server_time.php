<?php
// Получаем текущее время сервера в формате Unix timestamp
$timestamp = time();
// Возвращаем время в формате JSON
echo json_encode(array('timestamp' => $timestamp));
?>