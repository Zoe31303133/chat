<?php

    require_once('../asset/setup/DBconnect.php');
    

    $uid1 = $_GET['uid1'];
    $uid2 = $_GET['uid2'];

    if(!get_room_id($uid1, $uid2))
    {
        echo create_room($uid1, $uid2);
    }
    else
    {
        echo get_room_id($uid1, $uid2);
    }
    


    function get_room_id($uid1, $uid2){

        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
        $sql = "select t.room_id from 
        (select c.room_id , uid from paticipants as p join chatrooms as c on p.room_id=c.room_id where type=\"onebyone\" and uid in ($uid1,$uid2) ) as t  
        group by t.room_id
        having count(t.room_id)= 2;";
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row =  mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $row['room_id'];
        // return $result->num_rows;
    }

    function create_room($uid1, $uid2)
    {
        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
        
        do
        {
            $room_id = randStr();
            $sql = "insert into chatrooms(room_id, type) values (\"$room_id\", \"oneByone\");";
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_execute($stmt);
        }
        while(mysqli_errno($conn)==1062);
        
    
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        add_room_participant($room_id, $uid1, $uid2);

        return $room_id;
    }

    function add_room_participant($room_id, $uid1, $uid2){
        $conn = connection();
        $stmt = mysqli_stmt_init($conn);

        $sql = "insert into paticipants(room_id, uid) values (\"$room_id\", \"$uid1\"), (\"$room_id\", \"$uid2\");";
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);
    
        
    
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

    function randStr(){

        $result ="";
        $code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $len = rand(1,5);
        
        for($i=0; $i<$len; $i++)
        {
            $index=rand(0, 61);
            $result .= $code[$index];
        }

        return $result;
    }
?>


<!-- --
select * from paticipants;


-- create new chatroom (oneByone)
1.  
    insert into chatrooms(room_id, type) values ("", "oneByone");
2. 
    insert into paticipants(uid, room_id) values (,""), (,"");


-- get oneBtone room_id
select t.room_id from 
(select c.room_id , uid from paticipants as p join chatrooms as c on p.room_id=c.room_id where type="onebyone" and uid in (1,2) ) as t  
group by t.room_id
having count(t.room_id)= 2;
 -->

