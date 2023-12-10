<?php
session_start();

var_dump($_SESSION);

require_once('../chatroom/change_user_status.php');

$uid = $_SESSION['uid'];

session_unset();

change_user_status("offline", $uid);
?>