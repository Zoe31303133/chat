<?php
session_start();
require_once('../asset/setup/DBconnect.php');
require_once('../chatroom/change_user_status.php');

if(!isset($_POST['uid']))
{ die; }

$uid = $_POST['uid'];

if(!user_exist($uid))
{
    echo "no_user";
    die;
}


$password = $_POST['password'];
$encrypted_password = encrypt($password);


logIn($uid, $encrypted_password);


//TODO: 與signUp.inc.php的function重複
function encrypt($password)
{
    //TODO: 實作加密
    return $password;
}

function user_exist($uid){
    $sql = "select count(id) from users where id = ?";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if($row['count(id)']==0)
    {
        return false;
    }
    else
    {
        return true;
    }

    
}

function setSession($uid)
{
    $_SESSION['uid']=$uid;
}

function logIn($uid, $encrypted_password)
{
    //TODO: psw 改成 encrypted_password
    $sql = "select count(id) from users where id = ? and password = ?;";

    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    //TODO: psw 改成 encrypted_password
    mysqli_stmt_bind_param($stmt, 'ss', $uid, $encrypted_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    

    if($row['count(id)']==0)
    {
        echo "wrong_password";
    }
    else
    {
        echo "login_success";
        setSession($uid);
        change_user_status('online', $uid);
        
    }
}



?>