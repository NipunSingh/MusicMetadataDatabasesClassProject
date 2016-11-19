<?php 
    session_start(); 
    if (isset($_SESSION['username'])) {
        header("Location: index.php"); 
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Sign In</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="regContainer form-center-small">
            <h1 class="well">Sign In</h1>
            <?php
                if (isset($_SESSION['error'])) {
                    if (strcmp($_SESSION['error'], "INVALID") == 0) {
                        echo "<div class='alert alert-warning'>Invalid Username/Password</div>";
                    }
                    unset($_SESSION['error']);
                }
            ?>
            <div class="col-lg-12 well">
                <div class="row">
                    <form id = "main-form" action="signInUser.php" method="post">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name='username' id='username' placeholder="Username" class="form-control" required>
                            </div>				
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name = "password" data-minlength="8" id = "password" placeholder="Password" class="form-control" pattern=".{8,20}"  required title="8-20 characters">
                            </div>
                            <button type="submit" class="btn btn-lg btn-info">Sign In</button>
                            <div>
                                <a href="signup.php">Create account</a>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>