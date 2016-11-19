<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';
$user_q = "SELECT id, is_admin FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
$results = mysqli_query($conn,$user_q);
$user_info = mysqli_fetch_assoc($results);
$user_id = $user_info["id"];
$is_admin = $user_info["is_admin"];

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp($is_admin, "1") == 0) {
    $new_admin = $_GET['u_id'];
    $new_q = "SELECT id, is_admin FROM `music`.`web_user` where `id`='".$new_admin."'";
    $new_r = mysqli_query($conn,$new_q);
    $new_info = mysqli_fetch_assoc($new_r);
    $new_is_admin = $new_info["is_admin"];
    
    if (strcmp ($user_id, $new_admin) == 0) {
        header("Location: userManagement.php");
    }
    else if (strcmp ($new_is_admin, "1") == 0) {
        $update_q = "UPDATE `music`.`web_user` SET `is_admin`='0' where `id`='".$new_admin."'";
        mysqli_query($conn, $update_q);
        header("Location: userManagement.php");
    } else {
        $update_q = "UPDATE `music`.`web_user` SET `is_admin`='1' where `id`='".$new_admin."'";
        mysqli_query($conn, $update_q);
        header("Location: userManagement.php");
    }
    mysqli_close($conn);
} else {
    header("Location: index.php");
}