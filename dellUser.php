<?php

// Initialize the session
function listUsers($permit, $link) {

            global $index;
            $sql = "SELECT username, permit FROM users";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if ($row["permit"] == $permit) {
                    $user = $row["username"];
                    echo "<div>
                        <input type='checkbox' id=".$index." name=".$index." value=".$user.">
                        <label for=".$index.">".$user."</label>";
                    $index++;

                    /*if($row["permit"] == 0){&
                        echo"élève";
                    }elseif ($row["permit"] == 1) {
                        echo"professeur";
                    }elseif ($row["permit"] == 2) {
                        echo"admin";
                }*/
                    echo "<br>";
                }
                }
            } else {
                echo "0 resultats";
            }
        
}



require_once "config.php";
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 2){
    header("location: login.php");
    exit;
}

 
 $username = "";
 $index = 1;
// Define variables and initialize with empty values

 $username_err = "";



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    global $index;

    $sql = "SELECT count(*) FROM `users` WHERE 1";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $size = $row["count(*)"];
                }
            }
    
    
    for ($i = 1; $i <= $size; $i++) {

        if(isset($_POST[$i])){
            $username = trim($_POST[$i]);
            $sql = "DELETE FROM users WHERE users.username = ?";
        
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // Set parameters
                $param_username = $username;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    $username = "";
                    $username_err = "";
                    //header("location: login.php");
                    // Check if username exists
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un utilisateur</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Supprimer un utilisateur</h2>
            <br/>
            <h3>Administrateurs</h3>
            <?php
                listUsers(2, $link);
            ?>
            <br/>
            <h3>Professeurs</h3>
            <?php
                listUsers(1, $link);
            ?>
            <h3>Elèves</h3>
            <?php
                listUsers(0, $link);
            ?>
          
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Supprimer">
                <a href="admin.php" class="btn btn-warning">retour</a>
            </div>
        </form>
    </div>    
</body>
</html>