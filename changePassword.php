<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);
    
    if (strcmp ($username, $_SESSION['username']) != 0) {
        header("Location: index.php");
    } else {
        $update_q = "UPDATE `music`.`web_user` SET password='".$password."' where `username`='".$username."'";
        mysqli_query($conn, $update_q);
        mysqli_close($conn);
        $_SESSION['message'] = "OK_RESET";
        header("Location: accountEdit.php");
    }
} else {
    header("Location: index.php");
}