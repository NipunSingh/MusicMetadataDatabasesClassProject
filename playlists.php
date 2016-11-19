<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Playlists</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <h3>Create New Playlist</h3>
        <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" class="form-inline" action="createPlaylist.php" method="post">
                        <div class="col-sm-4 form-group">
                            <label>Name</label>
                            <input type="text" name='name' id='name' placeholder="Name" class="form-control" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Description</label>
                            <input type="text" name='description' id='description' placeholder="Description" class="form-control" required>
                        </div>
                        <div class="col-sm-2 form-group">
                            <button type="submit" class="btn btn-md btn-info">Create</button>
                        </div>
                    </form>
            </div>
        </div>
        <h3>Your Playlists</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                    $playlist_q = "SELECT id, name, description FROM `music`.`playlist` WHERE `user_id`='".$user_id."'";
                    $playlists = $conn->query($playlist_q);
                    if ($playlists->num_rows > 0) {
                        while($row = $playlists->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["name"]."</td><td>".$row["description"]."</td><td><a href='viewPlaylist.php?p_id=".$row["id"]."'>View Playlist</a>";
                            echo "</td></tr>";
                        }
                    }
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
        <?php include 'footer.php'; ?>
    </body>
</html>