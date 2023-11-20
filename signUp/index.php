<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="signUp\index.js"></script>
    <title>SIGN UP</title>
</head>
<body>
    <form id="form" action="/signUp/signUp.inc.php" method="POST">
    <input type="file" id="photo" name="photo">
    <input type="text" placeholder="name" id="name" name="name">
    <input type="text" placeholder="password" id="password" name="password">
    <input type="text" placeholder="password again" id="password_again" name="password_again">

</form>

<button id="send" >送出</button>    
    

</body>
</html>
