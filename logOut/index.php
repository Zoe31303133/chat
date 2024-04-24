<?php
    session_start();

    require_once('../asset/setup/DBconnect.php');
    require_once('../chatroom/change_user_status.php');

    $uid = $_SESSION['uid'];

    change_user_status("offline", $uid);

    session_destroy();
    $_SESSION = [];
    
?>