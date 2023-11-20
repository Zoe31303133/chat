<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="chatroom\index.js"></script>
    <link rel="stylesheet" href="../asset/include/css.css">
    <title>CHAT ROOM</title>
</head>
<body>
    <div class="navbar">
        <a class="navbar_btn" href="logIn">登入</a>
        <a class="navbar_btn" href="signUp">註冊</a>
        <button id="logOut_btn" class="navbar_btn"  >登出</button>
    </div>
    <div class="body">
        <div class="contact_list">
            <div class="contact"></div>
        </div>
        <div class="chat">
            <div class="message_area">
            </div>
            <div class="message_input">
                <input type="text" class="message_input_text" placeholder="say something...">
                <button id="message_input_sendBtn" class="message_input_sendBtn" >SEND</button>
            </div>
        </div>
    </div>
</body>
</html>