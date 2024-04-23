<?php

require_once('../asset/setup/DBconnect.php');
ini_set("file_uploads", "On" );
echo sys_get_temp_dir();

if(isset($_POST['id']))
{
    
    $id = $_POST['id'];
    if(user_exist($id))
    { 
        echo "user_exist"; 
    }
    else
    {
        var_dump($_POST);
        $name = $_POST['name'];
        $password = $_POST['password'];
        $encrypted_password = encrypt($password);
        $new_userId = addUser($id , $name, $encrypted_password);
        echo "success";
    }

 
    $target_dir = "../file/";
    $file_extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $new_userId. "." .$file_extension;
    echo $target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
}

//TODO:完成encrypt()
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

function encrypt($password)
{
    return "psw";
}

function addUser($id, $name, $encrypted_password){
    $create_time = date("Y-m-d H:i:s");

    $conn = connection();
 
    mysqli_begin_transaction($conn);
    
    $stmt = mysqli_stmt_init($conn);
    
    $sql = "insert into users (id, name, password, datetime) values (?,?,?,?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $id, $name, $encrypted_password, $create_time);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_commit($conn);
    mysqli_close($conn);
    
}
?>

