<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $song_id = mysql_real_escape_string($_POST['song']);
    $playlist_id = mysql_real_escape_string($_POST['p_id']);
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    $play_user_q = "SELECT user_id FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
    $play_user_id = $conn->query($play_user_q)->fetch_row()[0];
    if (strcmp ($user_id, $play_user_id) != 0) {
        header("Location: playlists.php");
    } else {
        $insert_q = "INSERT INTO `music`.`playlist_song` (playlist_id, song_id)
        VALUES ('$playlist_id','$song_id')";
        mysqli_query($conn, $insert_q);
        mysqli_close($conn);
        header("Location: viewPlaylist.php?p_id=".$playlist_id."");
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $playlist_id = $_GET['p_id'];
    $song_id = $_GET['s_id'];
    
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    $play_user_q = "SELECT user_id FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
    $play_user_id = $conn->query($play_user_q)->fetch_row()[0];
    if (strcmp ($user_id, $play_user_id) != 0) {
        header("Location: playlists.php");
    } else {
        $insert_q = "INSERT INTO `music`.`playlist_song` (playlist_id, song_id)
        VALUES ('$playlist_id','$song_id')";
        mysqli_query($conn, $insert_q);
        mysqli_close($conn);
        header("Location: viewPlaylist.php?p_id=".$playlist_id."");
    }
} else {
    header("Location: songs.php");
}