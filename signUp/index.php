

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="signUp\index.js"></script>
    <link rel="stylesheet" href="chatroom/toggle.css">
    <link rel="stylesheet" href="signUp/css.css">
    
    <title>SIGN UP</title>
</head>
<body>
    
        <?php require_once("../not_logIn_navbar.php"); ?>


    <div id="body">
        <form id="signUp_form" action="/signUp/signUp.inc.php" method="POST" enctype="multipart/form-data">
            
            <div id="edit_photo_wrapper" class="form_input">
                <img src="file/default.jpg" alt="X" id="edit_photo" class="">
                <div id="photo_edit_button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil" viewBox="0 0 16 16">
                        <path
                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                    </svg>
                    <input type="file" id="photo" name="fileToUpload" class="form_input opacity-0 position-absolute">
                    
                </div>
            </div>
            <input type="text" placeholder="ID" id="id" name="id" class="form_input">
            <input type="text" placeholder="name" id="name" name="name" class="form_input">
            <input type="text" placeholder="phone" id="phone" name="phone" class="form_input">
            <input type="text" placeholder="email" id="email" name="email" class="form_input">
            <input type="text" placeholder="password" id="password" name="password" class="form_input"> 
            <input type="text" placeholder="password again" id="password_again" name="password_again" class="form_input">
            <button id="send_btn" class="btn btn-secondary" >送出</button>
        </form>
    </div>

    
    

</body>
</html>
