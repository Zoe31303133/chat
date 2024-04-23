<?php
    session_start();
    
    require_once('../asset/setup/DBconnect.php');

    $my_uid = $_GET['my_uid'];
    echo( get_contacts($my_uid));



    function get_contacts($my_uid){
            $sql = "select id, name, status from users where id != ?;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 's', $my_uid);

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $array = array();
            while ($row= mysqli_fetch_assoc($result)){
                array_push($array, $row);
            };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            

            return json_encode($array);
    }
