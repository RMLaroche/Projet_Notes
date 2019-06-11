<?php

require_once "config.php";
function listUsers($permit, $link) {
            $sql = "SELECT username, permit FROM users";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if ($row["permit"] == $permit) {
                    echo $row["username"]/*. " - " */;
                    //echo " "; echo "<input type=submit id=$row[username] name=$row[username] value=$row[username]>";

                    /*if($row["permit"] == 0){
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

function listMatieres($link) {
    $sql = "SELECT username, libelle FROM users
            RIGHT JOIN matieres on matieres.user_ID = users.id ORDER BY username";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo $row["username"]. " - " ;
                    echo $row["libelle"];
                    echo "<br>";
                }
            } else {
                echo "0 resultats";
            }
    }




// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 2){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Bonjour, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Vous êtes connecté en tant qu'<b>administrateur<b>.</h1>
    </div>
    <p>
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
        
        <div class="page-header">
        <a href="register.php" class="btn btn-info">Ajouter un utilisateur</a>
        <a href="dellUser.php" class="btn btn-primary">Supprimer un utilisateur</a>
        </div>
        <h3>Matières</h3>
        <?php
            listMatieres($link)
        ?>
        <div class="page-header">
        <a href="ajouterMatiere.php" class="btn btn-info">Ajouter une matière</a>
        <a href="supprimerMatiere.php" class="btn btn-primary">Supprimer une matière</a>
        </div>

        <div class="page-header">
        <a href="reset-password.php" class="btn btn-warning">Changer de mot de passe</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </p>
</body>
</html>     