<?php 
    session_start();

    if(empty($_SESSION["csrf_token"])){
        $_SESSION["csrf_token"] = bin2hex(random_bytes(64));
        $_SESSION["expire_time"] = time() + 1800;
    }

    $csrf_token = $_SESSION["csrf_token"];
    $expire_time = $_SESSION["expire_time"];

?>