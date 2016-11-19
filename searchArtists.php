<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

$user_q = "SELECT id, is_admin FROM `music`.`web_user` where `username`='".$_SESSION['username']."'";
$results = mysqli_query($conn,$user_q);
$user_info = mysqli_fetch_assoc($results);
$user_id = $user_info["id"];

// Referenced http://stackoverflow.com/questions/21144023/livesearch-php-and-ajax
// and http://www.w3schools.com/php/php_ajax_livesearch.asp

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = mysql_real_escape_string($_POST['vals']);
    $select_q = "SELECT id, name FROM `music`.`artist` WHERE name LIKE '%".$query."%'";
    $artists = $conn->query($select_q);
    $result = "";
    if ($artists->num_rows > 0 && strcmp("", $query) != 0) {
        $result = "<ul class='no-bullet-list'>";
        while($row = $artists->fetch_assoc()) {
            $result = $result."<li><a href='addArtistFavorite.php?u_id=".$user_id."&a_id=".$row["id"]."'><span class='bold-name'>".$row["name"]."</span></a></li>";
        }
        $result = $result."</ul>";
    }
    $result = $result."";
    echo $result;
    mysqli_close($conn);
} else {
    header("Location: songs.php");
}