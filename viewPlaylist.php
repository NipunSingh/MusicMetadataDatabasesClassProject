<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';
    $playlist_id = $_GET['p_id'];
    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
    $play_user_q = "SELECT user_id, name, description FROM `music`.`playlist` WHERE `id`='".$playlist_id."'";
    $play_results = $result=mysqli_query($conn,$play_user_q);
    $play_r = mysqli_fetch_assoc($play_results);
    $play_user_id = $play_r["user_id"];
    $playlist_name = $play_r["name"];
    $playlist_description = $play_r["description"];
    
    if (strcmp ($user_id, $play_user_id) != 0) {
        header("Location: playlists.php");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>View Playlist</title>
        <!--Referenced http://stackoverflow.com/questions/21144023/livesearch-php-and-ajax
            and http://www.w3schools.com/php/php_ajax_livesearch.asp
        -->
        <script>
            function getQueryData(query) {
                $.post("searchSongs.php", {vals:query, playlist:"<?php echo $playlist_id ?>"}, function (data) {
                    $("#searchResults").html(data);
                });
            }
        </script>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li><a href="playlists.php">Playlists</a></li>
          <li class="active"><?php echo $playlist_name; ?></li>
        </ol>
        <h3><?php echo $playlist_name; ?>  <small class="text-muted"><a href='editPlaylist.php?p_id=<?php echo $playlist_id; ?>' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a></small></h3>
        <small class="text-muted"><?php echo $playlist_description; ?></small>
        <h4>Add song</h4>
        <div class="col-lg-12 well well-sm">
            <div class="row">
                <form id = "main-form" class="form-inline" method="post">
                    <div class="col-sm-4 form-group">
                        <label>Search:</label>
                        <input type="text" size="30" onkeyup="getQueryData(this.value)">
                        <div id="searchResults" class="autocomplete-search"></div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Artist</th>
                    <th>Genre</th>
                    <th>Length</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $songs_q = "SELECT `music`.`song`.id, `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`genre`.name as genre_name, `music`.`song`.length FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id LEFT JOIN `music`.`genre` ON `music`.`genre`.id = `music`.`song`.genre_id WHERE `music`.`song`.id IN (SELECT song_id as id FROM `music`.`playlist_song` WHERE `playlist_id`='".$playlist_id."')";
                    //$songs_q = "SELECT song_id FROM `music`.`playlist_song` WHERE `playlist_id`='".$playlist_id."'";
                    $songs = $conn->query($songs_q);
                    if ($songs->num_rows > 0) {
                        while($row = $songs->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["name"]."</td><td>".$row["artist_name"]."</td><td>".$row["genre_name"]."</td><td>".$row["length"]."<td><a href='removeSongPlaylist.php?p_id=".$playlist_id."&s_id=".$row["id"]."' title='Remove from Playlist'><span class='glyphicon glyphicon-remove'></span></a>";
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