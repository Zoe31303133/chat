//DONE

<?php

session_start();

require_once('change_user_status.php');

if(!isset($_SESSION['uid']))
{
    return false;
}
else
{
    $uid=$_SESSION['uid'];
}

require_once('../asset/setup/DBconnect.php');

if(isset($_GET['status']))
{
    change_user_status($_GET['status'], $uid);
}

?>