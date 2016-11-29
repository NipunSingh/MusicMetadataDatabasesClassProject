<?php
	include_once 'connection.php';
	$artists_q = "SELECT `music`.`artist`.spotify_id, `music`.`artist`.id FROM `music`.`artist`";
	$artists = $conn->query($artists_q);
    if ($artists->num_rows > 0) {
        while($row = $artists->fetch_assoc()) {
        	$base_url = "https://api.spotify.com/v1/artists/";
			$full_url = $base_url . $row["spotify_id"];
			//echo $full_url;
			$spotify_response = file_get_contents($full_url);
			$obj = json_decode($spotify_response, true);
			//echo "<li>".$obj['followers']['total']."</li>";
			$followers = $obj['followers']['total'];
			$insert_q = "INSERT INTO artist_data (followers, timestamp, artist_id) VALUES (" . $obj['followers']['total'] . ", NOW(), " . $row["id"] .");";
			//echo "<li>".$insert_q."</li>";
			$excute = $conn->query($insert_q);
        }
    }
?>