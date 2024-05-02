

<!DOCTYPE html>
    <html lang="en">
        <head>

            <?php require_once("../asset/include/header.php") ?>

            <script src="index.js"></script>
            <link rel="stylesheet" href="css.css">
            <title>SIGN UP</title>
            
        </head>
        <body>
            
                <?php require_once("../asset/include/not_logIn_navbar.php"); ?>


            <div id="body">
                <div id="signUp_form_wrapper" >
                <form id="signUp_form" action="/chat/signUp/signUp.inc.php" method="POST" enctype="multipart/form-data">
                    
                    <div id="edit_photo_wrapper" class="form_input">
                        <img src="/chat/asset/include/default_user.jpg" alt="X" id="edit_photo" class="">
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
                    <input type="text" placeholder="Name" id="name" name="name" class="form_input">
                    <input type="text" placeholder="Phone" id="phone" name="phone" class="form_input">
                    <input type="text" placeholder="Email" id="email" name="email" class="form_input">
                    <input type="text" placeholder="Password" id="password" name="password" class="form_input"> 
                    <input type="text" placeholder="Password again" id="password_again" name="password_again" class="form_input">
                </form>
                <button id="send_btn" class="btn btn-secondary" >送出</button>
                </div>
            </div>

            
            

        </body>
    </html>
