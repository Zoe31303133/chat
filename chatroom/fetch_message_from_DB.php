<?php
    session_start();
    
    require_once('../asset/setup/DBconnect.php');

    $room_id = $_POST['room_id'];

    
    echo get_message($room_id);


    function get_message($room_id){
            $sql = "select sentbyuid, text from messages where room_id = \"{$room_id}\" limit 10;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
                $array = array();
                while ($row= mysqli_fetch_assoc($result)){

                    $inner_array = array($row['sentbyuid'], $row['text']);
                    array_push($array, $inner_array);
                    
                };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            
            

            return json_encode($array);
    }
