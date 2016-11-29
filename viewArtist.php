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
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">Artists</li>
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

                          <div id="myDiv" style="width: 480px; height: 400px;"><!-- Plotly chart will be drawn inside this DIV --></div>
                            <script>
                                var data = [
                                {
                                    //x: ['2013-10-04 22:23:00', '2013-10-04 23:23:00', '2013-10-05 02:23:00'],
                                    //y: [173000, 335000, 400000],
                                    <?php 
                                                    $artist_data_q = "SELECT `music`.`artist_data`.followers, `music`.`artist_data`.timestamp FROM `music`.`artist_data` WHERE `music`.`artist_data`.artist_id = '".$artist_id."'";
                $artist_data_results = $conn->query($artist_data_q);
                $followers_string = "";
                $date_string = "";
                if ($artist_data_results->num_rows > 0) {
                                while($data_row = $artist_data_results->fetch_assoc()) {
                                    //echo "<li>".$data_row["followers"]."</li>";
                                    $followers_string = $followers_string.$data_row["followers"].",";
                                    $date_string = $date_string."'".$data_row["timestamp"]."',";
                                }
                                $trimmed_followers = trim($followers_string, ",");
                                $trimmed_dates = trim($date_string, ",");
                                echo "x: [".$trimmed_dates."],";
                                echo "y: [".$trimmed_followers."],";

                            }
                                ?>
                                    type: 'scatter',
                                }];
                                var layout = {
                                              title: 'Spotify Followers Graph',
                                              xaxis: {
                                                title: 'Dates',
                                                titlefont: {
                                                  family: 'Courier New, monospace',
                                                  size: 18,
                                                  color: '#7f7f7f'
                                                }
                                              },
                                              yaxis: {
                                                title: 'Follower Count',
                                                titlefont: {
                                                  family: 'Courier New, monospace',
                                                  size: 18,
                                                  color: '#7f7f7f'
                                                }
                                              }
                                            };
                                Plotly.newPlot('myDiv', data, layout);
                            </script>
                    </div>
                </div>
            <?php
            } else {
                header("Location: index.php");
            }
        ?>
    </body>
</html>