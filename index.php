<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Home</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
            <div>YOU ARE LOGGED IN <a href="logout.php" class="btn btn-success">Log Out</a></div>
        <?php include 'footer.php'; ?>
    </body>
</html>