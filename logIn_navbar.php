<?php
    session_start();
?>

<div class="navbar">

        <div>
            <img src="logo.jpg" class="navbar_logo" alt="x">
        </div>

        <div class="navbar_control">
            <div class="navbar_control_user">

                <img src="＃" alt="X" class="my_photo user_img">
                <span><?php echo $_SESSION['uid']?> </span>
                <button id="logout_btn" class="navbar_btn btn btn-outline-secondary" >登出</button>

            </div>
        </div>
    </div>