<?php

    session_start();

    require_once('../asset/setup/DBconnect.php');


    if(!isset($_SESSION['uid'])){ return false; }


        $text = $_POST['text'];
        $sentbyuid =$_POST['sentbyuid'];
        $datetime = date("Y-m-d H:i:s");
        $room_id =$_POST['room_id'];

        
        if(!validate()){ return false; }

        $sql = "insert into messages (text, sentbyuid, datetime, room_id) values (? , ? , ?, ?)";

        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $text, $sentbyuid, $datetime, $room_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);


    #region Function 


    function validate(){
        
        if($_POST['sentbyuid'] != $_SESSION['uid']) 
        {
            return false;
        }

        if(strlen($_POST['text'])>200) 
        {
            return false;
        }
        
        if(!preg_match("/^[a-zA-Z0-9]{1,5}$/", $_POST['room_id']))
        {
            return false;
        }

        if(!is_paticipant()){
            
            
            return false;
        }

        return true; 
    }

    function is_paticipant(){

        $sql = "select uid from paticipants where uid = ? and room_id= ?;";

        $conn = connection();
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['uid'], $_POST['room_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
     
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
  
        return $result->num_rows;

    }
    
    #endregion
?>