<?php
session_start();
require_once('../asset/setup/DBconnect.php');
require_once('../chatroom/change_user_status.php');

if(!isset($_POST['uid']))
{ die; }

validate($_POST);

$uid = $_POST['uid'];
user_exist($uid);

$password = $_POST['password'];

logIn($uid, $password);

function validate($logIn_form){

    foreach($logIn_form as $key => $value){

        $value = trim($value);

        switch($key){
            case "uid":
                $pattern = "/^[A-Za-z0-9\-\_]{1,25}$/";
                break;

            case "password":
                $pattern = "/^[A-Za-z0-9\-\_]{1,50}$/";
                break;
        }
        
        if(!preg_match($pattern, $value))
                {
                    report_format_error($key);
                }
    }
}

function report_format_error($field){
    header("HTTP/1.0 400 format error ". "(" . $field . ")");
    die;
}

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
        echo "no_user";
        die;
    }
    
}

function setSession($uid)
{
    $_SESSION['uid']=$uid;
}

function logIn($uid, $password)
{
    //TODO: psw 改成 encrypted_password
    $sql = "select password from users where id = ?;";

    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    //TODO: psw 改成 encrypted_password
    mysqli_stmt_bind_param($stmt, 's', $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    

    if(password_verify($password,$row['password']))
    {
        echo "login_success";
        setSession($uid);
        change_user_status('online', $uid);
    }
    else
    {  
        echo "wrong_password";
    }
}



?>