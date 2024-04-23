<?php
    session_start();
    
    require_once('../asset/setup/DBconnect.php');

    echo(get_user_info());

    function get_user_info(){
            $sql = "select id, name, phone, email from users where id = ?;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 's', $_SESSION['uid']);

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row= mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            

            return json_encode($row);
    }
