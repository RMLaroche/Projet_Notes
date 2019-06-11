<?php
require_once "config.php";
function listMatieres($link) {
	$username = $_SESSION["username"];
	global $index;
    $sql = "SELECT libelle FROM matieres
            RIGHT JOIN users on matieres.user_ID = users.id 
            WHERE users.username = '$username'";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                	$matiere = $row["libelle"];
                    echo '<a href="matiere.php?matiere='.$matiere.'" class="btn btn-info">'.$matiere.'</a>';
                    $index++;
                    echo "<br>";
                }
            } else {
                echo "0 resultats";
            }
    }



// Initialize the session
session_start();
  $username = $_SESSION["username"];
  $index = 1;

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 1){
    header("location: login.php");
    exit;
}

	




?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Bonjour, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Vous êtes connecté en tant que <b>professeur<b>.</h1>
    </div>
    <p>
        </div>
        <h3>Vos matières</h3>
        <?php
            listMatieres($link)
        ?>
        <div class="page-header">
        <a href="reset-password.php" class="btn btn-warning">Changer de mot de passe</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </p>
</body>
</html>