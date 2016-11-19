<?php session_start(); 
    if (isset($_SESSION['username'])) {
        header("Location: index.php"); 
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Sign Up</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="regContainer form-center-small">
            <h1 class="well">Sign Up Form</h1>
            <?php
                if (isset($_SESSION['error'])) {
                    if (strcmp($_SESSION['error'], "USER_EXISTS") == 0) {
                        echo "<div class='alert alert-warning'>A user with that username already exists!</div>";
                    }
                    unset($_SESSION['error']);
                }
            ?>
            <div class="col-lg-12 well">
               <div class="row">
                    <form id = "main-form" action="createaccount.php" method="post">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name='username' id='username' placeholder="Username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name='email' id='email' placeholder="Email Address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <small id="passwordHelpInline" class="text-muted">
                                    Must be 8-20 characters long.
                                </small>
                                <input type="password" name = "password" data-minlength="8" id = "password" placeholder="Password" class="form-control" pattern=".{8,20}" required title="8-20 characters">
                            </div>
                            <button type="submit" class="btn btn-lg btn-info">Sign Up</button>
                        </div>
                   </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>