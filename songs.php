<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
    include_once 'connection.php';

    $user_q = "SELECT id, is_admin FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
    $results = mysqli_query($conn,$user_q);
    $user_info = mysqli_fetch_assoc($results);
    $user_id = $user_info["id"];
    $is_admin = $user_info["is_admin"];
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Songs</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">Songs</li>
        </ol>
        <?php if (strcmp ($is_admin, "1") == 0) { ?>
        <h3>Create New Song</h3>
        <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" action="createSong.php" method="post">
                        <div class="col-sm-6 form-group">
                            <label>Name:</label>
                            <input type="text" name='name' id='name' placeholder="Name" class="form-control" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Length:</label>
                            <input type="text" name='length' id='length' placeholder="length" class="form-control" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Artist:</label>
                            <select name="artist">
                                <?php
                                    $artist_q = "SELECT id, name FROM `music`.`artist`";
                                    $artists = $conn->query($artist_q);
                                    if ($artists->num_rows > 0) {
                                        while($row = $artists->fetch_assoc()) {
                                            echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-sm-2 form-group">
                            <button type="submit" class="btn btn-md btn-info">Create</button>
                        </div>
                    </form>
            </div>
        </div>
        <?php } ?>
        <h3>All Songs</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Artist</th>
                    <th>Length</th>
                    <th>Popularity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                    //$songs_q = "SELECT `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`genre`.name as genre_name, `music`.`song`.length, `music`.`song`.popularity FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id LEFT JOIN `music`.`genre` ON `music`.`genre`.id = `music`.`song`.genre_id ORDER BY `music`.`song`.popularity DESC";
                    $songs_q = "SELECT `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`song`.length, `music`.`song`.popularity FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id ORDER BY `music`.`song`.popularity DESC";
                    $songs = $conn->query($songs_q);
                    if ($songs->num_rows > 0) {
                        while($row = $songs->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["name"]."</td><td>".$row["artist_name"]."</td><td>".$row["length"]."</td><td>".$row["popularity"];
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