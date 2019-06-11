<?php
require_once "config.php";
function getTests($link, $matiere) {
    $arrayTests = array();
    $sql = 'SELECT tests.libelle FROM tests
            RIGHT JOIN matieres on matieres.ID_matiere = tests.matiere_ID 
            WHERE matieres.libelle = "'.$matiere.'"';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($arrayTests, $row["libelle"]);
                }
            } else {
                echo "0 resultats";
            }
    return $arrayTests;
    }
function getMatieres($link){
    $arrayMat = array();
    $sql = 'SELECT matieres.Libelle FROM matieres';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($arrayMat, $row["Libelle"]);
                }
            } else {
                echo "0 resultats";
            }
    return $arrayMat;


}
function getNote($link, $eleve, $evaluations, $matiere) {
        $sql = 'SELECT id FROM users
        WHERE username = "'.$eleve.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eleve_id = $row["id"];
            }
        }
        $sql = 'SELECT ID_matiere FROM matieres
                WHERE libelle = "'.$matiere.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $matiere_id = $row["ID_matiere"];
            }
        }


        $sql = 'SELECT ID_test FROM tests
        WHERE libelle = "'.$evaluations.'" AND matiere_id = "'.$matiere_id.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $test_id = $row["ID_test"];
            }
        }



    $sql = 'SELECT note FROM notes WHERE user_ID = "'.$eleve_id.'" AND test_ID = "'.$test_id.'"';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return $row["note"];
                }
            } else {
                return "NN";
            }
    }
    function getCommentaire($link, $eleve, $evaluations, $matiere) {
        $sql = 'SELECT id FROM users
        WHERE username = "'.$eleve.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eleve_id = $row["id"];
            }
        }
        $sql = 'SELECT ID_matiere FROM matieres
                WHERE libelle = "'.$matiere.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $matiere_id = $row["ID_matiere"];
            }
        }
        $sql = 'SELECT ID_test FROM tests
        WHERE libelle = "'.$evaluations.'" AND matiere_id = "'.$matiere_id.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $test_id = $row["ID_test"];
            }
        }



    $sql = 'SELECT commentaire FROM notes WHERE user_ID = "'.$eleve_id.'" AND test_ID = "'.$test_id.'"';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return $row["commentaire"];
                }
            } else {
                return "";
            }
    }

function creertabeau($link,$matieres){
    global $eleve_username;
    //pour chaque utilisateur
        echo "<style>
            table, td, th {
              border: 1px solid black;
            }

            table {
              border-collapse: collapse;
              width: 100%;
            }

            th {
              height: 50px;
            }
            </style><table>";    
        foreach ($matieres as $mat) {
            echo "<tr>";
            echo "<td><b>".$mat."</b></td>";
            $tests=getTests($link, $mat);
            foreach ($tests as $eval) {
                //donner la note
                $note = getNote($link,$eleve_username,$eval, $mat);
                echo '<td><b>'.$eval.'</b><br>';
                if($note>20 && $note <= 24){
                    echo'Mention:<br>';
                    switch ($note) {
                    case 21:
                        echo "<b>Non noté</b>;";
                        break;
                    case 22:
                        echo "
                        <b>Non acquis</b>";
                        break;
                    
                    case 23:
                        echo "
                        <b>En cours d'acquisition</b>";
                        break;
                    case 24:
                        echo "<b>Acquis</b>";
                        break;
                    }

                }else{
                    echo'Note:<br><b>'.$note.'</b>';
                
                }
                echo "<br>";
                $commentaire = getCommentaire($link,$eleve_username,$eval, $mat);
                echo 'Commentaire:<br><textarea id="commentaire|'.$eleve_username.'|'.$eval.'" name="commentaire|'.$eleve_username.'|'.$eval.'">'.$commentaire.'</textarea>&nbsp;</td>';

            }
            echo "</tr>";
        //nouvelle ligne
        }
        echo "</table>";
        
    }
// Initialize the session
session_start();

$eleve_username = $_SESSION["username"];
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 0){
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
        <h1>Bonjour, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Vous êtes connecté en tant qu'<b>élève</b>.</h1>
    </div>
    <p>
        <h3>Vos Evaluations</h3>
        <?php

        creertabeau($link,getMatieres($link));
    
        ?>
        <br>
        <a href="reset-password.php" class="btn btn-warning">Changer de mot de passe</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </p>
</body>
</html>