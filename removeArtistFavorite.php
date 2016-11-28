<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_val_id = $_GET['u_id'];
    $artist_id = $_GET['a_id'];
    
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    
    if (strcmp ($user_id, $user_val_id) != 0) {
        header("Location: index.php");
    } else {
        $delete_q = "DELETE FROM `music`.`user_favorite_artist` WHERE user_id='".$user_id."' AND artist_id='".$artist_id."'";
        mysqli_query($conn, $delete_q);
        mysqli_close($conn);
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}