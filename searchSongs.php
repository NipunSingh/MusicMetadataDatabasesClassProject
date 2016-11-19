<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
}

include_once 'connection.php';

// Referenced http://stackoverflow.com/questions/21144023/livesearch-php-and-ajax
// and http://www.w3schools.com/php/php_ajax_livesearch.asp

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = mysql_real_escape_string($_POST['vals']);
    $p_id = mysql_real_escape_string($_POST['playlist']);
    $select_q = "SELECT `music`.`song`.id as id, `music`.`song`.name as name, `music`.`artist`.name as artist_name FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`song`.artist_id = `music`.`artist`.id WHERE `music`.`song`.name LIKE '%".$query."%'";
    $songs = $conn->query($select_q);
    $result = "";
    if ($songs->num_rows > 0 && strcmp("", $query) != 0) {
        $result = "<ul class='no-bullet-list'>";
        while($row = $songs->fetch_assoc()) {
            $result = $result."<li><a href='addSongPlaylist.php?p_id=".$p_id."&s_id=".$row["id"]."'><span class='bold-name'>".$row["name"]."</span><small class='text-muted'> - ".$row["artist_name"]."</small></a></li>";
        }
        $result = $result."</ul>";
    }
    $result = $result."";
    echo $result;
    mysqli_close($conn);
} else {
    header("Location: songs.php");
}