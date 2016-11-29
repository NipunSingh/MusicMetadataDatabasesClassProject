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
        <title>Genres</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">Genres</li>
        </ol>
        <?php if (strcmp ($is_admin, "1") == 0) { ?>        
        <?php } ?>
        <h3>All Genres</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Genre Name</th>
                    <th>Artists In Genre</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                    //$songs_q = "SELECT `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`genre`.name as genre_name, `music`.`song`.length, `music`.`song`.popularity FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id LEFT JOIN `music`.`genre` ON `music`.`genre`.id = `music`.`song`.genre_id ORDER BY `music`.`song`.popularity DESC";
                    $songs_q = "SELECT `music`.`song`.name, `music`.`artist`.name as artist_name, `music`.`song`.length, `music`.`song`.popularity FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id ORDER BY `music`.`song`.popularity DESC";
                    $genres_q = "SELECT `music`.`genre`.name, `music`.`genre`.id FROM `music`.`genre` ORDER BY `music`.`genre`.name ASC";
                    $genres = $conn->query($genres_q);
                    if ($genres->num_rows > 0) {
                        while($row = $genres->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["name"]."</td><td>";
                            $artists_in_genre_q = "SELECT `music`.`artist`.name FROM `music`.`artist` LEFT JOIN `music`.`artist_in_genre` ON `music`.`artist`.id = `music`.`artist_in_genre`.artist_id WHERE `music`.`artist_in_genre`.genre_id = ".$row["id"]." ORDER BY `music`.`artist`.name ASC";
                            $artists = $conn->query($artists_in_genre_q);
                            while($inner_row = $artists->fetch_assoc()) {
                                echo "<li>".$inner_row["name"]."</li>";
                            }

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