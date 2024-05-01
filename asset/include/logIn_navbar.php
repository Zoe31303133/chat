<?php
    session_start();
?>

<div class="navbar">

        <div>
            <a href="http://localhost:4000/chatroom" >
                <img src="/asset/include/logo.jpg" class="navbar_logo" alt="x">
            </a>
        </div>

        <div class="navbar_control">
            <div class="navbar_control_user">

                <a href="http://localhost:4000/profile">
                    <img src="＃" alt="X" class="my_photo user_img">
                </a>
                <span><?php echo $_SESSION['uid']?> </span>
                <button id="logout_btn" class="navbar_btn btn btn-outline-secondary" >登出</button>

            </div>
        </div>
    </div>