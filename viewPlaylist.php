<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
    $playlist_id = $_GET['p_id'];
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    $play_user_q = "SELECT user_id FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
    $play_user_id = $conn->query($play_user_q)->fetch_row()[0];
    if (strcmp ($user_id, $play_user_id) != 0) {
        header("Location: playlists.php");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>View Playlist</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <h3>Add Song</h3>
        <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" class="form-inline" action="addSongPlaylist.php" method="post">
                        <div class="col-sm-4 form-group">
                            <input type="hidden" name="p_id" value="<?php echo $playlist_id; ?>">
                            <select name="song">
                                <?php
                                    $song_q = "SELECT id, name FROM `music`.`song`";
                                    $songs = $conn->query($song_q);
                                    if ($songs->num_rows > 0) {
                                        while($row = $songs->fetch_assoc()) {
                                            echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div> 
                        <div class="col-sm-2 form-group">
                            <button type="submit" class="btn btn-md btn-info">Add Song</button>
                        </div>
                    </form>
            </div>
        </div>
        <h3>Your Playlists</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Artist</th>
                    <th>Genre</th>
                    <th>Length</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $songs_q = "SELECT `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`genre`.name as genre_name, `music`.`song`.length FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id LEFT JOIN `music`.`genre` ON `music`.`genre`.id = `music`.`song`.genre_id WHERE `music`.`song`.id IN (SELECT song_id as id FROM `music`.`playlist_song` WHERE `playlist_id`='".$playlist_id."')";
                    //$songs_q = "SELECT song_id FROM `music`.`playlist_song` WHERE `playlist_id`='".$playlist_id."'";
                    $songs = $conn->query($songs_q);
                    if ($songs->num_rows > 0) {
                        while($row = $songs->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["name"]."</td><td>".$row["artist_name"]."</td><td>".$row["genre_name"]."</td><td>".$row["length"];
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