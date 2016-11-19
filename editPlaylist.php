<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysql_real_escape_string($_POST['name']);
        $description = mysql_real_escape_string($_POST['description']);
        $playlist_id = mysql_real_escape_string($_POST['p_id']);
        $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
        $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
        $play_user_q = "SELECT user_id, name, description FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
        $play_results = mysqli_query($conn,$play_user_q);
        $play_r = mysqli_fetch_assoc($play_results);
        $play_user_id = $play_r["user_id"];

        if (strcmp ($play_user_id, $user_id) != 0) {
            header("Location: playlists.php");
        } else {
            $update_q = "UPDATE `music`.`playlist` SET `name`='".$name."', `description`='".$description."' where `id`='".$playlist_id."'";
            mysqli_query($conn, $update_q);
            header("Location: playlists.php");
        }
    } else {
        $playlist_id = $_GET['p_id'];
        $play_user_q = "SELECT user_id, name, description FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
        $play_results = $result=mysqli_query($conn,$play_user_q);
        $play_r = mysqli_fetch_assoc($play_results);
        $play_user_id = $play_r["user_id"];
        $playlist_name = $play_r["name"];
        $playlist_description = $play_r["description"];
        
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Edit Playlist</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="regContainer form-center-small">
            <h3>Update "<?php echo $playlist_name; ?>" Playlist</h3>
            <div class="col-lg-12 well">
               <div class="row">
                    <form id = "main-form" action="editPlaylist.php" method="post">
                        <div class="col-sm-12">
                            <input type="hidden" value="<?php echo $playlist_id; ?>" name="p_id">
                            <div class="col-sm-12 form-group">
                                <label>Name:</label>
                                <input type="text" name='name' id='name' value="<?php echo $playlist_name; ?>" placeholder="Name" class="form-control" required>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label>Description:</label>
                                <input type="text" name='description' id='description' value="<?php echo $playlist_description; ?>" placeholder="Description" class="form-control" required>
                            </div>
                            <div class="col-sm-4 form-group">
                                <button type="submit" class="btn btn-lg btn-info">Update</button>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
<?php 
    }
    mysqli_close($conn);
?>