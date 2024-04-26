<?php
    session_start();


    if(time()>$_SESSION["expire_time"])
    {
        unset($_SESSION["expire_time"]);
    }

    if($client_csrf_token != $_SESSION["csrf_token"])
    {
        header("HTTP/1.0 403 Forbidden");
        die;
    }


?>