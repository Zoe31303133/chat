<?php

require_once('../asset/setup/DBconnect.php');
ini_set("file_uploads", "On" );


// Main

valiadate_user_info();
check_id_availible();
sign_up_user();

// Function

function response_error($httpcode, $code, $message){

    // error code 1XX : user info validation error 
    // error code 2XX : user photo validation error 
    // error code 3XX : server error 

    header('Content-type: application/json');

    http_response_code(400);

    $response = [];
    $response['code'] = $code;
    $response['message'] = $message;

    echo json_encode($response); 

    exit();
}

function check_id_availible(){
    
    $id = $_POST['id'];

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
    
    if(!$row['count(id)']==0)
    {
        $error_message = "使用者ID已被註冊";
        response_error(400, "102", $error_message);
    }
}

function valiadate_user_info(){

    foreach($_POST as $key => $value){

        $value = trim($value);

        $validatrion_rule = [
            "id"=>["pattern"=>"/^[A-Za-z0-9\-\_]+$/", "length"=>25],
            "name"=>["pattern"=>"/^[A-Za-z0-9\-\_]+$/", "length"=>10],
            "phone"=>["pattern"=>"/^09[0-9]{8}$/", "length"=>10],
            "email"=>["pattern"=>"/^[a-zA-z0-9_]+@[a-zA-z0-9]+\.[a-zA-z0-9]+$/", "length"=>50],
            "password"=>["pattern"=>"/^[A-Za-z0-9\-\_]+$/", "length"=>50],
        ];

        if(isset($rule[$key])){
           
            if(!preg_match($validatrion_rule[$key]["pattern"], $value)||strlen($value)>$validatrion_rule[$key]["length"])
            {
                $error_message = $key."不符合格式要求";
                response_error(400, "102", $error_message);
            }
        }
    }
}

function valiadate_user_photo(){
    
    $is_image = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    if($is_image == false) {
        $error_message = "上傳檔案非圖檔"; 
        response_error(400, "201", $error_message);
    }

    //

    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $max_image_size = 500000/1000;
        $error_message = "檔案過大，請小於".$max_image_size."Byte";
        response_error(400, "202", $error_message);
    }

    //

    $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    $allow_file_types = ["jpg", "png", "jpeg"];

    if(!in_array($imageFileType, $allow_file_types)) {
        $error_message = "圖檔不符格式，請上傳格式為 " . implode(", ", $allow_file_types) . " 之圖檔";
        response_error(400, "203", $error_message);
    }
}

function sign_up_user(){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $encrypted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try{

        if(!empty($_FILES['fileToUpload']["tmp_name"])||!empty($_FILES['fileToUpload']["size"]))
        {
            valiadate_user_photo();

            $target_dir = "../file/";
            $file_new_path = $target_dir . $id ;
            upload_user_photo($file_new_path);
        }
        
        add_user($id, $name, $phone, $email, $encrypted_password);
        echo "success";

    }
    catch(Exception $e){

        if (file_exists($file_new_path)) {
            unlink($file_new_path);
        };

        $error_message = $e->getMessage();
        response_error(500, "301", $error_message);

    }

    
}

function upload_user_photo($file_new_path){

    if (file_exists($file_new_path)) {
        unlink($file_new_path);
    }

    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file_new_path)) {
        echo "upload_error";
    }
}

function add_user($id, $name, $phone, $email, $encrypted_password){

    $conn = connection();
    $stmt = mysqli_stmt_init($conn);

    $create_time = date("Y-m-d H:i:s");

    $sql = "insert into users (id, name, phone, email, password, datetime) values (?,?,?,?,?,?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $id, $name, $phone, $email,$encrypted_password, $create_time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
