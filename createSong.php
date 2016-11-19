<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysql_real_escape_string($_POST['name']);
    $artist_id = mysql_real_escape_string($_POST['artist']);
    $genre_id = mysql_real_escape_string($_POST['genre']);
    $length = mysql_real_escape_string($_POST['length']);

    $insert_q = "INSERT INTO `music`.`song` (id, name, artist_id, genre_id, length)
    VALUES (NULL,'$name','$artist_id','$genre_id','$length')";
    mysqli_query($conn, $insert_q);
    mysqli_close($conn);
    header("Location: songs.php"); 
} else {
    header("Location: songs.php");
}