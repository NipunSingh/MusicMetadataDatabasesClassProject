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

if ($_SERVER["REQUEST_METHOD"] == "POST"  && strcmp($is_admin, "1") == 0) {
    $name = mysql_real_escape_string($_POST['name']);
    $popularity = mysql_real_escape_string($_POST['popularity']);

    $insert_q = "INSERT INTO `music`.`artist`(`id`, `name`, `popularity`) VALUES (NULL,'$name','$popularity')";
    mysqli_query($conn, $insert_q);
    mysqli_close($conn);
    header("Location: artists.php"); 
} else {
    header("Location: artists.php");
}