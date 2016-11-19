<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
    $playlist_id = $_GET['p_id'];
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    $play_user_q = "SELECT user_id, name FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
    $play_results = $result=mysqli_query($conn,$play_user_q);
    $play_r = mysqli_fetch_assoc($play_results);
    $play_user_id = $play_r["user_id"];
    $playlist_name = $play_r["name"];
    
    if (strcmp ($user_id, $play_user_id) != 0) {
        header("Location: playlists.php");
    } else {
        $delete_q = "DELETE FROM `music`.`playlist` WHERE id='".$playlist_id."'";
        mysqli_query($conn, $delete_q);
        mysqli_close($conn);
        header("Location: playlists.php");
    }
?>