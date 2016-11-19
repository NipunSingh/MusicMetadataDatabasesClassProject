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
    $is_admin = $user_info["is_admin"];

    if (strcmp($is_admin, "1") != 0) {
        header("Location: index.php");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php include 'header.php'; ?>
        <title>User Management</title>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
          <li class="active">User Management</li>
        </ol>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>E-mail</th>
                    <th>Admin?</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $users_q = "SELECT * FROM `music`.`userlist`";
                    $users = $conn->query($users_q);
                    if ($users->num_rows > 0) {
                        while($row = $users->fetch_assoc()) {
                            if (strcmp($row["id"], $user_id) != 0) {
                                echo "<tr><td>";
                                echo $row["username"]."</td><td>".$row["email"]."</td>";
                                echo "<td><a href='toggleAdmin.php?u_id=".$row["id"]."' title='Click to toggle'>";
                                $admin_val = "";
                                if (strcmp($row["is_admin"], "1") == 0) {
                                    $admin_val = "YES";
                                } else {
                                    $admin_val = "NO";
                                }
                                echo $admin_val;
                                echo "</a></td>";
                                echo "<td><a href='deleteUser.php?u_id=".$row["id"]."' title='Delete'><span class='glyphicon glyphicon-remove'></span></a>";
                                echo "</td></tr>";
                            }
                        }
                    }
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>
        <?php include 'footer.php'; ?>
    </body>
</html>