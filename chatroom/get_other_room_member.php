<?php
    session_start();
    
    require_once('../asset/setup/DBconnect.php');

    function get_other_room_member($room_id, $uid){


            $sql = "select uid from paticipants where room_id = \"{$room_id}\" and uid != \"{$uid}\";";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $array = array();
            while ($row= mysqli_fetch_assoc($result)){
                array_push($array, $row['uid']);
            };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            return implode(",", $array);
    }
