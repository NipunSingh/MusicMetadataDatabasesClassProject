<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);
    $email = mysql_real_escape_string($_POST['email']);

    $user_q = "SELECT * FROM `music`.`web_user` where `username`='".$username."'";
    $user_r = mysqli_query($conn,$user_q);
    $num_users = mysqli_num_rows($user_r);

    if($num_users >= 1) 
    {
        $_SESSION['error'] = "USER_EXISTS";
        header("Location: signup.php");
        mysqli_close($conn);
    } else {
        $insert_q = "INSERT INTO `music`.`web_user` (id, username, password, email)
        VALUES (NULL,'$username','$password','$email')";
        mysqli_query($conn, $insert_q);
        mysqli_close($conn);
        $_SESSION['username'] = $_POST['username'];
        unset($_SESSION['error']);
        header("Location: index.php"); 
    }
} else {
    header("Location: signin.php");
}