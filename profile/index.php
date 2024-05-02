<?php
    session_start();
    require_once("../csrf_token.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once("../asset/include/header.php") ?>
    <script src="/chat/chatroom/index.js"></script>
    <script src="index.js"></script>
    <link rel="stylesheet" href="css.css">

    <title>Document</title>
    
</head>

<body>

    <?php require_once("../asset/include/logIn_navbar.php"); ?>

    <div id="body" class="d-flex justify-content-center p-5">
        <div id="user_info">
        <form id="update_form" action="/chat/profile/update_user_info.inc.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo($_SESSION['csrf_token']) ?>">
                <div id="user_photo_name_wrapper">
                    <div id="edit_photo_wrapper">
                        <img src="＃" alt="X" id="edit_photo">
                        <div id="photo_edit_button" class="d-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil" viewBox="0 0 16 16">
                                <path
                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                            </svg>
                            <input type="file" id="photo" name="photo" class="form_input opacity-0 position-absolute">
                            
                        </div>
                    </div>
                    <div id="userName">
                            <input type="text" id="inputName" name="name" class="form-control" disabled="disabled">
                    </div>
                </div>
                <div>
                    
                    <div class="mb-3 row justify-content-between">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div>
                            <input type="text" id="inputEmail" name="email" class="form-control "  disabled="disabled" >
                        </div>
                    </div>
                    <div class="mb-3 row justify-content-between">
                        <label for="inputPhone" class="col-sm-2 col-form-label">Phone</label>
                        <div>
                            <input  type="text" id="inputPhone" name="phone" class="form-control" disabled="disabled">
                        </div>
                    </div>
                </div>

        </form>
            <div class="control_btn d-flex justify-content-center">
                    <button id="edit_btn" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".control_btn">修改</button>
            </div>
            <div id="control_btn" class="control_btn d-flex flex-row justify-content-center d-none" data-bs-target="#userName" style="transition: none">
                    <button id="submit_btn" class="btn btn-success">送出</button>    
                    <button id="cancel_btn" class="btn btn-danger" data-toggle="collapse">取消</button>
            </div>
        </div>
    </div>


</body>

</html>