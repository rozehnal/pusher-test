<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

$pusher = new Pusher('31a1c5ee118d567a3eda', '69db0658fcd83f029c25', '143281');
$data = array('name' => session_id());
$user_id = session_id();
echo $pusher->presence_auth($_POST['channel_name'], $_POST['socket_id'], $user_id, $data);