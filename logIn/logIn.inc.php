<?php
session_start();
require_once('../asset/setup/DBconnect.php');

if(!isset($_POST['name']))
{ die; }

$name = $_POST['name'];
if(!user_exist($name))
{return false;}

$password = $_POST['password'];
$encrypted_password = encrypt($password);
logIn($name, $encrypted_password);


//TODO: 與signUp.inc.php的function重複
function encrypt($password)
{
    //TODO: 實作加密
    return "encrypted_password";
}

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
        echo "使用者不存在";
        return false;
    }
    else
    {
        return true;}
}

function logIn($name, $encrypted_password)
{
    $sql = "select count(name) from users where name = ? and password = ? ;";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $name, $encrypted_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    if($row['count(name)']==0)
    {
        echo "wrong_password";
        return false;
    }
    else
    {
        echo "login_success";
        return true;
    }
}


?>