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
    $del_user = $_GET['u_id'];
    
    if (strcmp ($user_id, $del_user) == 0) {
        header("Location: userManagement.php");
    }
    else {
        $delete_q = "DELETE FROM `music`.`web_user` WHERE id='".$del_user."'";
        mysqli_query($conn, $delete_q);
        mysqli_close($conn);
        header("Location: userManagement.php");
    }
    
} else {
    header("Location: index.php");
}