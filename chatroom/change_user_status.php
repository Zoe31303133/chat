<?php

function change_user_status($status, $uid){

    $sql = "update users set status = ? where id = ?;";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $uid);
    mysqli_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
?>