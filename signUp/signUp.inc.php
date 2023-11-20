<?php

require_once('../asset/setup/DBconnect.php');

if(isset($_POST['name']))
{
    
    $name = $_POST['name'];
    user_exist($name);
    
    $password = $_POST['password'];
    $encrypted_password = encrypt($password);
    addUser($name , $encrypted_password);


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
        echo "註冊成功！";
        return false;
    }
    else
    {
        echo "該使用者名稱已被使用！";
        return true;
    }
}

function encrypt($password)
{
    return "encrypted_password";
}

function addUser($name, $encrypted_password){
    $sql = "insert into users (name, password) values (?,?);";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $name, $encrypted_password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}


?>

