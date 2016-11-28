<?php session_start(); 
    if (!isset($_SESSION['username'])) {
        header("Location: signin.php"); 
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Home</title>
        <script>
            function getQueryData(query) {
                $.post("searchArtists.php", {vals:query}, function (data) {
                    $("#searchResults").html(data);
                });
            }
            function getQueryDataGenre(query) {
                $.post("searchGenres.php", {vals:query}, function (data) {
                    $("#searchResultsGenre").html(data);
                });
            }
        </script>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="col-lg-6">
            <h3>Add an artist to your favorites</h3>
            <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" class="form-inline" method="post">
                        <div class="col-sm-6 form-group">
                            <label>Search:</label>
                            <input type="text" size="30" onkeyup="getQueryData(this.value)">
                            <div id="searchResults" class="autocomplete-search"></div>
                        </div>
                    </form>
                </div>
            </div>
            <h3>Favorite Artists:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date added</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                        $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                        $artist_q = "SELECT `music`.`artist`.id, `music`.`artist`.name as name, `music`.`user_favorite_artist`.timestamp_val as time FROM `music`.`user_favorite_artist` LEFT JOIN `music`.`artist` on `music`.`user_favorite_artist`.artist_id = `music`.`artist`.id WHERE `music`.`user_favorite_artist`.user_id='".$user_id."'";
                        $artists = $conn->query($artist_q);
                        if ($artists->num_rows > 0) {
                            while($row = $artists->fetch_assoc()) {
                                echo "<tr><td>";
                                echo $row["name"]."</td><td>".$row["time"]."</td><td><a href='removeArtistFavorite.php?u_id=".$user_id."&a_id=".$row["id"]."'><span class='glyphicon glyphicon-remove'></span></a>";
                                echo "</td></tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <h3>Add a genre to your favorites</h3>
            <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" class="form-inline" method="post">
                        <div class="col-sm-6 form-group">
                            <label>Search:</label>
                            <input type="text" size="30" onkeyup="getQueryDataGenre(this.value)">
                            <div id="searchResultsGenre" class="autocomplete-search"></div>
                        </div>
                    </form>
                </div>
            </div>
            <h3>Favorite Genres:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date added</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                        $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                        $artist_q = "SELECT `music`.`genre`.id, `music`.`genre`.name as name, `music`.`user_favorite_genre`.timestamp_val as time FROM `music`.`user_favorite_genre` LEFT JOIN `music`.`genre` on `music`.`user_favorite_genre`.genre_id = `music`.`genre`.id WHERE `music`.`user_favorite_genre`.user_id='".$user_id."'";
                        $artists = $conn->query($artist_q);
                        if ($artists->num_rows > 0) {
                            while($row = $artists->fetch_assoc()) {
                                echo "<tr><td>";
                                echo $row["name"]."</td><td>".$row["time"]."</td><td><a href='removeGenreFavorite.php?u_id=".$user_id."&g_id=".$row["id"]."'><span class='glyphicon glyphicon-remove'></span></a>";
                                echo "</td></tr>";
                            }
                        }
                        mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>