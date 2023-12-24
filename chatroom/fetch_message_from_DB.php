<?php
    session_start();
    $sql = ('');
    require_once('../asset/setup/DBconnect.php');

    $room_id = $_GET['room_id'];  
    
    if($min_message_id = $_GET['min_message_id']){
        echo(get_previous_message($room_id, $min_message_id));
    }
    else
    {
        echo(get_last_message($room_id));
    };


    function get_last_message($room_id){

            $sql = "select id, sentbyuid ,text from (select * from messages where room_id = \"{$room_id}\" order by id desc limit 10) as desc_messages order by id ;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $result_object = new stdClass();
            $array = array();

            //TODO 重構重複部份
            $row= mysqli_fetch_assoc($result);
            $inner_array = array($row['sentbyuid'], $row['text']);
            array_push($array, $inner_array);
            $result_object -> min_message_id = $row['id'];

            while ($row= mysqli_fetch_assoc($result)){
                $inner_array = array($row['sentbyuid'], $row['text']);
                array_push($array, $inner_array);
                
            };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            

            $result_object -> messages = $array;

            return json_encode($result_object);
    }

    function get_previous_message($room_id, $min_message_id){

        $sql = "select id, sentbyuid ,text from (select * from messages where room_id = \"{$room_id}\" and id < \"{$min_message_id}\" order by id desc limit 10) as desc_messages order by id ;";
        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $result_object = new stdClass();
        $array = array();

        if($row= mysqli_fetch_assoc($result))
        {
            $inner_array = array($row['sentbyuid'], $row['text']);
            array_push($array, $inner_array);
            $result_object -> min_message_id = $row['id'];

            while ($row= mysqli_fetch_assoc($result)){
                $inner_array = array($row['sentbyuid'], $row['text']);
                array_push($array, $inner_array);
                
            };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            

            $result_object -> messages = $array;
        }
        else{
            $result_object = null;
        }

        return json_encode($result_object);
}
