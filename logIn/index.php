<?php
    session_start();
    var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="logIn\index.js"></script>
    <link rel="stylesheet" href="logIn/css.css">
    <link rel="stylesheet" href="chatroom/toggle.css">
    <title>LOGIN</title>
</head>

<body>
    <?php require_once("../not_logIn_navbar.php"); ?>
    
    <div id="body">
        <div id="logIn_form">
            <input type="text" placeholder="id" id="id" name="id" class="form_input">
            <input type="text" placeholder="password" id="password" name="password" class="form_input">
            <div >
                <a href="http://localhost:4000/signUp" class="redirect_link">註冊帳號</a>
            </div>
            <button id="send_btn" class="btn btn-secondary">送出</button>
        </div>
    </div>



</body>

</html>