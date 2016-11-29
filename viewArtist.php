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
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $artist_id = $_GET['a_id'];
                $artist_q = "SELECT * FROM `music`.`artist` where `id`='".$artist_id."'";
                $results = mysqli_query($conn,$artist_q);
                $artist_info = mysqli_fetch_assoc($results);
                $genre_q = "SELECT `music`.`genre`.name as genre_name FROM `music`.`artist_in_genre` LEFT JOIN `music`.`genre` ON `music`.`genre`.id = `music`.`artist_in_genre`.genre_id WHERE `music`.`artist_in_genre`.artist_id = '".$artist_id."'";
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><?php echo $artist_info["name"]; ?></h4></div>
                    <div class="panel-body"><p>Popularity:  <?php echo $artist_info["popularity"]; ?></p>
                        <p>Genres:</p>
                        <ul>
                        <?php 
                            $genres = $conn->query($genre_q);
                            if ($genres->num_rows > 0) {
                                while($row = $genres->fetch_assoc()) {
                                    echo "<li>".$row["genre_name"]."</li>";
                                }
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            <?php
            } else {
                header("Location: index.php");
            }
        ?>
    </body>
</html>