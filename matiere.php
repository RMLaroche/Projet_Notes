<?php
require_once "config.php";
    function getTests($link) {
    $matiere  = $_GET["matiere"];
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

    function getEleves($link) {
    $arrayEleves = array();
    $sql = 'SELECT username FROM users WHERE permit = 0';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($arrayEleves, $row["username"]);
                }
            } else {
                echo "0 resultats";
            }
    return $arrayEleves;
    }

    function getNote($link, $eleve, $evaluations) {
    	global $matiere;
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
    function getCommentaire($link, $eleve, $evaluations) {
    	global $matiere;
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
    function creertabeau($link,$utilisateurs,$tests){
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
			</style><table><thead><tr>
                                <th>Eleve</th>";
        foreach ($tests as $note) {
                echo "<th>".$note."</th>";
            }                        
                                
        echo "</tr></thead><tbody>";
        foreach ($utilisateurs as $eleve) {
            echo "<tr>";
            echo "<td>".$eleve."</td>";
            foreach ($tests as $eval) {
                //donner la note
                $note = getNote($link,$eleve,$eval);
                echo '<td>';
                if($note>20 && $note <= 24){
                    echo'Mention:<br><select id="note|'.$eleve.'|'.$eval.'" name="note|'.$eleve.'|'.$eval.'">';
                    switch ($note) {
                    case 21:
                        echo "<option selected value=21>Non noté</option>
                        <option value=22>Non acquis</option>
                        <option value=23>en cours d'acquisition</option>
                        <option value=24>Acquis</option>";
                        break;
                    case 22:
                        echo "<option value=21>Non noté</option>
                        <option selected value=22>Non acquis</option>
                        <option value=23>en cours d'acquisition</option>
                        <option value=24>Acquis</option>";
                        break;
                    
                    case 23:
                        echo "<option value=21>Non noté</option>
                        <option value=22>Non acquis</option>
                        <option selected value=23>En cours d'acquisition</option>
                        <option value=24>Acquis</option>";
                        break;
                    case 24:
                        echo "<option value=21>Non noté</option>
                        <option value=22>Non acquis</option>
                        <option value=23>en cours d'acquisition</option>
                        <option selected value=24>Acquis</option>";
                        break;
                    }
                    echo'</select>';

                }else{
                    echo'Note:<br><input  type="text" id="note|'.$eleve.'|'.$eval.'" name="note|'.$eleve.'|'.$eval.'" size="1" value ="'.$note.'">';
                
                }
                echo "<br>";
                $commentaire = getCommentaire($link,$eleve,$eval);
                echo 'Commentaire:<br><textarea id="commentaire|'.$eleve.'|'.$eval.'" name="commentaire|'.$eleve.'|'.$eval.'">'.$commentaire.'</textarea>&nbsp;</td>';

            }
            echo "</tr>";
        //nouvelle ligne
        }
        echo "</table>";
        
    }
    function creerNote($link, $eleve, $test, $note, $commentaire){}
    function mettreAJour($link,$utilisateurs,$tests){
    	$cpt=0;
    	global $matiere;


    	foreach ($utilisateurs as $eleve) {
            foreach ($tests as $eval) {
            	$element = "note|".str_replace(' ', '_', $eleve)."|".str_replace(' ', '_', $eval);
            	$elementc = "commentaire|".str_replace(' ', '_', $eleve)."|".str_replace(' ', '_', $eval);
            	if($_POST[$element] != "NN"){
            		$sql = 'SELECT ID_matiere FROM matieres WHERE libelle = "'.$matiere.'"';
					$result = $link->query($sql);

					if ($result->num_rows > 0) {
					    while($row = $result->fetch_assoc()) {
					        $matiere_id = $row["ID_matiere"];
					    }
					}

            		$sql = 'SELECT ID_test FROM tests
			        WHERE libelle = "'.$eval.'" AND matiere_id = "'.$matiere_id.'"';
					$result = $link->query($sql);

					if ($result->num_rows > 0) {
					    while($row = $result->fetch_assoc()) {
					        $test_id = $row["ID_test"];
					    }
					}
            		$sql = 'SELECT ID_note FROM notes INNER JOIN users ON user_ID = ID INNER JOIN tests ON ID_test = test_ID WHERE username = ? AND tests.ID_test = ?';
        
			        if($stmt = mysqli_prepare($link, $sql)){
			            // Bind variables to the prepared statement as parameters
			            mysqli_stmt_bind_param($stmt, "si", $param_username, $param_test);
			            
			            // Set parameters
			            $param_username = $eleve;
			            $param_test = $test_id;
			            
			            // Attempt to execute the prepared statement
			            if(mysqli_stmt_execute($stmt)){
			                /* store result */
			                mysqli_stmt_store_result($stmt);
			                $stmt->bind_result($id_note_existante);
			                if(mysqli_stmt_num_rows($stmt) >= 1){
			                    //////////////////////////////////// Si la note éxiste déjà :
			                    if(mysqli_stmt_fetch($stmt)){
				                	$sql = "UPDATE notes SET note = ?, commentaire = ? WHERE ID_note = ?";
        
							        if($stmt = mysqli_prepare($link, $sql)){
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "isi", $param_note,$param_commentaire, $param_id_note);
							            
							            // Set parameters
							            $param_note = $_POST[$element];
							            $param_commentaire = $_POST[$elementc];
							            $param_id_note = $id_note_existante;
							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt)){
							            	$cpt +=1;
							            }
							        }
			                	}
			                }else{
					        	global $matiere;
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
						        WHERE libelle = "'.$eval.'" AND matiere_id = "'.$matiere_id.'"';
								$result = $link->query($sql);

								if ($result->num_rows > 0) {
								    while($row = $result->fetch_assoc()) {
								        $test_id = $row["ID_test"];
								    }
								}
					        	$sql = "INSERT INTO notes (note, commentaire, user_ID, test_ID) VALUES (?, ?, ?, ?)";
		       
						        if($stmt = mysqli_prepare($link, $sql)){
						            $param_note = $_POST[$element];
						            $param_commentaire = $_POST[$elementc];
						            $param_eleve_id = $eleve_id;
						            $param_eval_id = $test_id;
						 
						            // Bind variables to the prepared statement as parameters
						            mysqli_stmt_bind_param($stmt,"isii", $param_note, $param_commentaire, $param_eleve_id, $param_eval_id);
						            
						            // Set parameters
						                        // Attempt to execute the prepared statement
						            if(mysqli_stmt_execute($stmt)){
						            	$cpt +=1;
						            }
						        }
						    }
			            } else{
			                echo "Oops! Une erreur s'est produite !";
			            }
			        }
			        mysqli_stmt_close($stmt);
            	}
            }
        }
        //header("Refresh:0");
//        echo $cpt." notes modifiées";
    }


// Initialize the session
session_start();
  $username = $_SESSION["username"];
  $matiere = $_GET["matiere"];
  $index = 1;

/////////////////////////////////////////////////
$sql = 'SELECT user_ID FROM matieres
        WHERE libelle = "'.$matiere.'"';
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $prof_id = $row["user_ID"];
    }
}
$sql = 'SELECT id FROM users
        WHERE username = "'.$username.'"';
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $username_id =$row["id"];
    }
}
/////////////////////////////////////////////////

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 1 || $username_id != $prof_id){


    header("location: login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	mettreAJour($link,getEleves($link),getTests($link));


}








?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $_GET["matiere"]; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1><b><?php echo htmlspecialchars($_GET["matiere"]); ?></b></h1>
    </div>
    <form method="post">
    <p>
        </div>
        <h3>Vos Evaluations</h3>
        <?php

        creertabeau($link,getEleves($link),getTests($link));
 	
        ?>
        <br>
        <input type="submit" class="btn btn-primary" value="Appliquer les modifications">
        <div class="page-header">
        <a href="ajouterEval.php?matiere=<?php echo $matiere; ?>" class="btn btn-info">Ajouter une évaluation</a>
        <a href="supprimerEval.php?matiere=<?php echo $matiere; ?>" class="btn btn-primary">Supprimer une évaluation</a>
        </div>
        <div class="page-header">
        <a href="prof.php" class="btn btn-warning">Retour</a>
    </p>
</form>
</body>
</html>