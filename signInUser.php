<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php"); 
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);

    $user_q = "SELECT * FROM `music`.`web_user` where `username`='".$username."'";
    $user_r = mysqli_query($conn,$user_q);
    $num_users = mysqli_num_rows($user_r);

    if ($num_users == 0) {  
        $_SESSION['error'] = "INVALID";
        header("Location: signin.php");
    } else {
        $password_q = "SELECT password FROM `music`.`web_user` where `username`='".$username."'";
        $stored_password = mysqli_query($conn,$password_q)->fetch_row()[0];;
        mysqli_close($conn);
        if (strcmp ($stored_password, $password) == 0) {
            $_SESSION['username'] = $_POST['username'];
            unset($_SESSION['error']);
            header("Location: index.php"); 
        } else {
            $_SESSION['error'] = "INVALID";
            header("Location: signin.php");
        }
    }
} else {
    header("Location: signin.php");
}
