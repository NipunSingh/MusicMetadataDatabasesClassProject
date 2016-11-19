<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
    $email_q = "SELECT email FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $stored_email = mysqli_query($conn,$email_q)->fetch_row()[0];;
    mysqli_close($conn);
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Edit Account</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="regContainer form-center-small">
            <?php
                if (isset($_SESSION['message'])) {
                    if (strcmp($_SESSION['message'], "OK_RESET") == 0) {
                        echo "<div class='alert alert-warning'>Password reset successfully!</div>";
                    } else if (strcmp($_SESSION['message'], "OK_EMAIL") == 0) {
                        echo "<div class='alert alert-warning'>Email changed successfully!</div>";
                    }
                    unset($_SESSION['message']);
                }
            ?>
            <div class="col-lg-12 well">
               <div class="row">
                    <form id = "main-form" action="changeEmail.php" method="post">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Username:</label>
                                <span><?php echo $_SESSION['username']; ?></span>
                                <input type="hidden" name='username' id='username' value="<?php echo $_SESSION['username']; ?>" placeholder="Username" class="form-control" required>
                            </div>
                            <label>Email Address</label>
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <input type="text" name='email' id='email' value="<?php echo $stored_email; ?>" placeholder="Email Address" class="form-control" required>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="submit" class="btn btn-lg btn-info">Update</button>
                                </div>
                            </div>
                        </div>
                   </form>
                   <form id = "main-form" action="changePassword.php" method="post">
                        <div class="col-sm-12">
                            <input type="hidden" name='username' id='username' value="<?php echo $_SESSION['username']; ?>" placeholder="Username" class="form-control" required>
                            <label>Password</label>
                            <small id="passwordHelpInline" class="text-muted">
                                Must be 8-20 characters long.
                            </small>
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <input type="password" name = "password" data-minlength="8" id = "password" placeholder="Password" class="form-control" pattern=".{8,20}" required title="8-20 characters">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="submit" class="btn btn-lg btn-info">Update</button>
                                </div>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>