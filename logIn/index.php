<?php
    session_start();
    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once("../asset/include/header.php") ?>

    <script src="index.js"></script>
    <link rel="stylesheet" href="css.css">
    <title>LOGIN</title>
    
</head>

<body>
    <?php require_once("../asset/include/not_logIn_navbar.php"); ?>
    
    <div id="body">
        <div id="logIn_form">
            <input type="text" placeholder="ID" id="id" name="id" class="form_input">
            <input type="text" placeholder="Password" id="password" name="password" class="form_input">
            <div >
                <a href="/chat/signUp" class="redirect_link">註冊帳號</a>
            </div>
            <button id="send_btn" class="btn btn-secondary">送出</button>
        </div>
    </div>



</body>

</html>