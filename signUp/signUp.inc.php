<?php

require_once('../asset/setup/DBconnect.php');
ini_set("file_uploads", "On" );

if(isset($_POST['id']))
{
    $new_userId  = $_POST['id'];
    if(user_exist($new_userId))
    { 
        echo("user_exist");
        die;
    }
    else
    { 
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
    }

 
    $target_dir = "../file/";
    $file_extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $new_userId. ".jpg" ;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "format_error";
        $uploadOk = 0;
    }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "size_too_large";
    $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "format_error";
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "upload_error";
    // if everything is ok, try to upload file
    } else {
    if (
        
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        addUser($new_userId , $name, $phone, $email, $encrypted_password);
        echo "success";
        
        } else {
        echo "upload_error";
    }
    }
}

function user_exist($id){
    $sql = "select count(id) from users where id = ?";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
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

function report_format_error($field){
    header("HTTP/1.0 400 format error ". "(" . $field . ")");
    die;
}

function valiadate($update_form){

    foreach($update_form as $key => $value){

        if($key=="uid"||$key=="photo"||$key=="csrf_token")
        {continue ;}

        $value = trim($value);

        switch($key){

            case "id":
                $pattern = "/^[A-Za-z0-9\-\_]{1,25}$/";
                $length = 25;
                break;

            case "name":
                $pattern = "/^[A-Za-z0-9\-\_]{1,10}$/";
                $length = 10;
                break;

            case "phone":
                $pattern = "/^09[0-9]{8}$/";
                $length = 10;
                break;

            case "email":
                $pattern = "/^[a-zA-z0-9_]+@[a-zA-z0-9]+\.[a-zA-z0-9]+$/";
                $length = 50;
                break;
        }
        
        if(!preg_match($pattern, $value)||strlen($value)>$length)
                {
                    report_format_error($key);
                }
    }
}

function addUser($id, $name, $phone, $email, $encrypted_password){
    $create_time = date("Y-m-d H:i:s");

    $conn = connection();
 
    mysqli_begin_transaction($conn);
    
    $stmt = mysqli_stmt_init($conn);
    
    $sql = "insert into users (id, name, phone, email, password, datetime) values (?,?,?,?,?,?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $id, $name, $phone, $email,$encrypted_password, $create_time);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_commit($conn);
    mysqli_close($conn);
}
?>

