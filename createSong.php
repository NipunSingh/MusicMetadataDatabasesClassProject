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

if (strcmp ($is_admin, "1") != 0) {
    header("Location: songs.php");
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string($_POST['name']);
    $artist_id = mysql_real_escape_string($_POST['artist']);
    $length = mysql_real_escape_string($_POST['length']);

    $insert_q = "INSERT INTO `music`.`song` (id, name, artist_id, length)
    VALUES (NULL,'$name','$artist_id','$length')";
    mysqli_query($conn, $insert_q);
    mysqli_close($conn);
    header("Location: songs.php"); 
} else {
    header("Location: songs.php");
}