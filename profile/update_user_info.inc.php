<?php

    session_start();    
    require_once("../asset/setup/DBconnect.php");

    if($_POST["uid"]!=$_SESSION["uid"])
    {
        header("HTTP/1.0 403 Forbidden");
        die;
    }

    if(!isset($_POST["name"])|!isset($_POST["phone"])||!isset($_POST["email"]))
    {
        header("HTTP/1.0 400 The update information is not filled out completely.");
        die;
    }

    echo($_FILES);
        
    valiadate($_POST);

    photo_is_upload() && update_user_photo($_POST["uid"]);
    
    update_user_info($_POST);





    // Funciion

    function report_format_error($field){
        header("HTTP/1.0 400 format error ". "(" . $field . ")");
        die;
    }

    function valiadate($update_form){

        foreach($update_form as $key => $value){

            if($key=="uid"||$key=="photo")
            {continue ;}

            $value = trim($value);

            switch($key){
                case "name":
                    $pattern = "/^[A-Za-z0-9\-\_]+$/";
                    $length = 10;
                    break;

                case "phone":
                    $pattern = "/^09[0-9]{8}$/";
                    $length = 10;
                    break;

                case "email":
                    $pattern = "/^[^@\s]+@[^@\s]+\.[a-zA-z0-9]+$/";
                    $length = 50;
                    break;
            }
            
            if(!preg_match($pattern, $value)||strlen($value)>$length)
                    {
                        report_format_error($key);
                    }
        }

    }



    function update_user_info($object){

            $uid = $object["uid"];
            $name = $object["name"];
            $phone = $object["phone"];
            $email = $object["email"];


            $sql = "update users set name = ?, email = ?, phone = ?  where id = ?;";
            $conn = connection();
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $_SESSION["uid"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

    }

    function photo_is_upload(){
        return $_FILES['photo']['size']!=0;
    }
    


    function update_user_photo($uid){

        $target_dir = "../file/";
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $target_file = $target_dir . $uid. "." .$file_extension;
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
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {

            rename($target_file, str_replace($file_extension, "jpg", $target_file));

            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
        }
    }