<?php

function change_user_status($status, $uid){

    $sql = "update users set status = '$status' where id = '$uid';";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);

    mysqli_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
?>