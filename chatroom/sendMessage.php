<?php

    session_start();

    require_once('../asset/setup/DBconnect.php');


    if(!isset($_SESSION['uid']))
    {
        return false;
    }
    else
    {
        $uid=$_SESSION['uid'];
        $sql = $_POST['sql'];

        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
    
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

    }
    
?>