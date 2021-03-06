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
?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>Search</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">Search</li>
        </ol>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_SESSION['error'])) {
                if (strcmp($_SESSION['error'], "INVALID") == 0) {
                    echo "<div class='alert alert-warning'>Invalid Search</div>";
                }
                unset($_SESSION['error']);
            }
            ?>
            <h3>Search Songs</h3>
            <div class="col-lg-12 well">
                <div class="row">
                    <form id = "main-form" action="search.php" method="post">
                        <div class="col-sm-12">
                            <?php for ($i = 1; $i <= 3; $i++) { ?>
                                <div class="form-group">
                                    <div class="col-sm-4 form-group">
                                        <label>Field <?php echo $i; ?></label>
                                        <select form="main-form" class='form-control' id="field-<?php echo $i; ?>" name="field-<?php echo $i; ?>" required>
                                            <option value="nothing">Please Select</option>
                                            <option value="`music`.`artist`.name">Artist Name</option>
                                            <option value="`music`.`artist`.popularity">Artist Popularity</option>
                                            <!--<option value="`music`.`artist`.followers">Artist Followers</option>-->
                                            <option value="`music`.`song`.name">Song Name</option>
                                            <option value="`music`.`song`.popularity">Song Popularity</option>
                                            <option value="`music`.`song`.length">Song Length</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Operator <?php echo $i; ?></label>
                                        <select form="main-form" class='form-control' id="operator-<?php echo $i; ?>" name="operator-<?php echo $i; ?>" required>
                                            <option value="=">=</option>
                                            <option value="!=">!=</option>
                                            <option value="<"><</option>
                                            <option value="<="><=</option>
                                            <option value=">">></option>
                                            <option value=">=">>=</option>
                                            <option value="LIKE">LIKE</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 form-group">
                                        <label>Value <?php echo $i; ?></label>
                                        <input type="text" name = "value-<?php echo $i; ?>" id = "value-<?php echo $i; ?>" placeholder="Value" class="form-control">
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <button type="submit" class="btn btn-lg btn-info">Search</button>
                        </div>
                    </form> 
                </div>
            </div>
            <?php
        }
        else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $field_1 = mysql_real_escape_string($_POST['field-1']);
            $operator_1 = mysql_real_escape_string($_POST['operator-1']);
            $value_1 = mysql_real_escape_string($_POST['value-1']);
            
            $field_2 = mysql_real_escape_string($_POST['field-2']);
            $operator_2 = mysql_real_escape_string($_POST['operator-2']);
            $value_2 = mysql_real_escape_string($_POST['value-2']);
            
            $field_3 = mysql_real_escape_string($_POST['field-3']);
            $operator_3 = mysql_real_escape_string($_POST['operator-3']);
            $value_3 = mysql_real_escape_string($_POST['value-3']);
            
            //Referenced http://stackoverflow.com/questions/15794179/create-a-dynamic-mysql-query-using-php-variables
            
            if (strcmp($field_1, "nothing") == 0 || strcmp($value_1, "") == 0) {
                $_SESSION['error'] = "INVALID";
                header("Location: search.php");
            } else {
                unset($sql);
                
                if ($field_1 && $value_1) {
                    if (strcmp($operator_1, "LIKE") == 0) {
                        $value_1 = "%".$value_1."%";
                    }
                    $sql[] = " ".$field_1." ".$operator_1." '$value_1' ";
                }
                
                if ($field_2 && $value_2  && strcmp($field_2, "nothing") != 0) {
                    if (strcmp($operator_2, "LIKE") == 0) {
                        $value_2 = "%".$value_2."%";
                    }
                    $sql[] = " ".$field_2." ".$operator_2." '$value_2' ";
                }
                
                if ($field_3 && $value_3  && strcmp($field_3, "nothing") != 0) {
                    if (strcmp($operator_3, "LIKE") == 0) {
                        $value_3 = "%".$value_3."%";
                    }
                    $sql[] = " ".$field_3." ".$operator_3." '$value_3' ";
                }
                
                //$query = "SELECT `music`.`song`.id, `music`.`song`.name, `music`.`song`.popularity as song_popularity, `music`.`song`.length, `music`.`artist`.name as artist_name, `music`.`artist`.popularity as artist_popularity, `music`.`artist`.followers as artist_followers FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id";
                
                $query = "SELECT `music`.`song`.id, `music`.`song`.name, `music`.`song`.length, `music`.`song`.popularity as song_popularity, `music`.`artist`.name as artist_name, `music`.`artist`.popularity as artist_popularity FROM `music`.`song` LEFT JOIN `music`.`artist` ON `music`.`artist`.id = `music`.`song`.artist_id";


                if (!empty($sql)) {
                    $query .= ' WHERE ' . implode(' AND ', $sql);
                }
                //echo $query;
                $results = $conn->query($query);
                if ($results->num_rows > 0) {
                    ?>
                     <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Song Name</th>
                                <th>Artist Name</th>
                                <th>Song Length</th>
                                <th>Song Popularity</th>
                                <th>Artist Popularity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $results->fetch_assoc()) {
                                echo "<tr><td>";
                                echo $row["name"]."</td><td>".$row["artist_name"]."</td><td>".$row["length"]."</td><td>".$row["song_popularity"]."</td><td>".$row["artist_popularity"];
                                echo "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<h4>The search returned no results</h4>";
                }
                    
                echo "<a href='search.php'>Return to search form</a>";
            }
            
            mysqli_close($conn);
        } else {
            header("Location: index.php");
        }
        ?>
    </body>
</html>
        
        