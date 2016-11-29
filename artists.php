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
        <title>Artists</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">Artists</li>
        </ol>
        <?php if (strcmp ($is_admin, "1") == 0) { ?>
        <h3>Create New Artist</h3>
        <div class="col-lg-12 well well-sm">
                <div class="row">
                    <form id = "main-form" action="createArtist.php" method="post">
                        <div class="col-sm-6 form-group">
                            <label>Name:</label>
                            <input type="text" name='name' id='name' placeholder="Name" class="form-control" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Popularity:</label>
                            <input type="text" name='popularity' id='popularity' class="form-control">
                        </div>
                        <div class="col-sm-2 form-group">
                            <button type="submit" class="btn btn-md btn-info">Create</button>
                        </div>
                    </form>
            </div>
        </div>
        <?php } ?>
        <h3>All Artists</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Popularity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $user_q = "SELECT id FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
                    $user_id = mysqli_query($conn,$user_q)->fetch_row()[0];
                    $artists_q = "SELECT * FROM `music`.`artist`";
                    $artists = $conn->query($artists_q);
                    if ($artists->num_rows > 0) {
                        while($row = $artists->fetch_assoc()) {
                            echo "<tr><td>";
//                            $bdate = $row["birth_date"];
//                            if (strcmp($row["birth_date"],"0000-00-00")==0) {
//                                $bdate = "-";
//                            }
//                            $ddate = $row["death_date"];
//                            if (strcmp($row["death_date"],"0000-00-00")==0) {
//                                $ddate = "-";
//                            }
                            echo "<a href='viewArtist.php?a_id=".$row["id"]."'>".$row["name"]."</a></td><td>".$row["popularity"];
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