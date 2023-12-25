<?php

require_once('../asset/setup/DBconnect.php');

if(isset($_POST['name']))
{
    
    $name = $_POST['name'];
    if(user_exist($name))
    { 
        echo "user_exist"; 
    }
    else
    {
        $password = $_POST['password'];
        $encrypted_password = encrypt($password);
        addUser($name , $encrypted_password);

        echo "success";
    }
}

//TODO:完成encrypt()
function user_exist($name){
    $sql = "select count(name) from users where name = ?";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    if($row['count(name)']==0)
    {
        
        return false;

    }
    else
    {
        return true;
    }
}

function encrypt($password)
{
    return "psw";
}

function addUser($name, $encrypted_password){
    $create_time = date("Y-m-d H:i:s");

    $sql = "insert into users (name, password, datetime) values (?,?,?);";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $name, $encrypted_password, $create_time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}


?>

