<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string($_POST['name']);
    $description = mysql_real_escape_string($_POST['description']);

    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];

    $insert_q = "INSERT INTO `music`.`playlist` (id, user_id, name, description)
    VALUES (NULL,'$user_id','$name','$description')";
    mysqli_query($conn, $insert_q);
    mysqli_close($conn);
    header("Location: playlists.php"); 
} else {
    header("Location: playlists.php");
}