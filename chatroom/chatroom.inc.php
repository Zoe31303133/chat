<?php
    session_start();
    require_once('../asset/setup/DBconnect.php');

    $result = get_contacts();
    echo json_encode($result);

    function get_contacts(){
    $sql = "select id, name from users;";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row= mysqli_fetch_all($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $row;
    }
?>