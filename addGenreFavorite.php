<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_val_id = $_GET['u_id'];
    $genre_id = $_GET['g_id'];
    
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    
    if (strcmp ($user_id, $user_val_id) != 0) {
        header("Location: index.php");
    } else {
        $insert_q = "INSERT INTO `music`.`user_favorite_genre` (user_id, genre_id)
        VALUES ('$user_id','$genre_id')";
        mysqli_query($conn, $insert_q);
        mysqli_close($conn);
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}