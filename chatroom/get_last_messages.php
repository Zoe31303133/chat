<?php
    session_start();
    
    require_once('../asset/setup/DBconnect.php');

    $my_uid = $_GET['my_uid'];
    echo( get_contacts($my_uid));



    function get_contacts($my_uid){
            $sql = "select t.room_id, uid, text, datetime, if(type=\"group\", t.room_id, uid) as photo 
            from (select room_id, text, datetime from messages 
                    where id in 
                    (select max(id) from messages as m join (select room_id from paticipants where uid= ?) as p on m.room_id = p.room_id  group by p.room_id) order by datetime desc) as t  
                    join paticipants as p on t.room_id=p.room_id  join chatrooms as c on p.room_id=c.room_id 
                    where uid!= ? order by datetime desc;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $my_uid, $my_uid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $array = array();
            while ($row= mysqli_fetch_assoc($result)){

                $row['text'] = preview_text($row['text']);
                array_push($array, $row);
            };

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            

            return json_encode($array);
    }

    
    function preview_text($text){

        if(strlen($text)>10)
        {
            $text = substr($text, 0, 15)." ...";
        }

        return $text;
        
    }