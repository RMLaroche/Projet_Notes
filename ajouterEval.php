<?php
require_once "config.php";
// Initialize the session
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
function listUsers($permit, $link) {

            global $index;
            $sql = "SELECT username, permit FROM users";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>Eleve</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>";
                while($row = $result->fetch_assoc()) {
                    if ($row["permit"] == $permit) {

                    $user = $row["username"];
                    echo "<tr>";
                    echo "<td>".$user."</td>";
                    echo "<td><div>
                        <input type='text' id=".$index." name=".$index." size='1'></td>";
                    $index++;
                    echo "</tr>";
                    
                }
                }
            } else {
                echo "0 resultats";
            }
            echo "</table>";
        
}

session_start();

if (!empty($_GET["matiere"])){
$_SESSION["matiere"] = $_GET["matiere"];
//header("location: supprimerEval.php");
}
$matiere = $_SESSION["matiere"];

$username = $_SESSION["username"];
$index = 1;

/////////////////////////////////////////////////
$sql = 'SELECT user_ID FROM matieres
        WHERE libelle = "'.$matiere.'"';
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $matiere_id = $row["user_ID"];
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

$sql = 'SELECT ID_matiere FROM matieres
        WHERE libelle = "'.$matiere.'"';
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            $id_matiere = $row["ID_matiere"];
            }
        }
/////////////////////////////////////////////////

 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 1 || $username_id != $matiere_id){
    header("location: login.php");
    exit;
}

 
 
 
 $username = "";
 $index = 1;
// Define variables and initialize with empty values

 $username_err = "";



// Processing form data when form is submitted
$test = "";
$test_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["test"]))){
        $test_err = "Entrez un nom d'évaluation";
    } else{
        // Prepare a select statement
        $sql = "SELECT ID_test FROM tests WHERE libelle = ? AND matiere_ID = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_test, $param_id_matiere);
            
            // Set parameters
            $param_id_matiere = $id_matiere;
            $param_test = trim($_POST["test"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $test_err = "Ce nom d'évaluation' est déjà pris";
                } else{
                    $test = trim($_POST["test"]);
                }
            } else{
                echo "Oops! Une erreur s'est produite !";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($test_err)){

       
        // Prepare an insert statement
        $sql = "INSERT INTO tests (libelle, matiere_ID) VALUES (?, ?)";



       
        if($stmt = mysqli_prepare($link, $sql)){
            $param_test = $test;
            $param_id_matiere = $id_matiere;
 
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"si", $param_test, $param_id_matiere);
            
            // Set parameters
                        // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                if (trim($_POST["typeN"]) == 1){
                    foreach (getEleves($link) as $eleve) {
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
                                WHERE libelle = "'.$test.'" AND matiere_id = "'.$matiere_id.'"';
                                $result = $link->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $test_id = $row["ID_test"];
                                    }
                                }
                                $sql = "INSERT INTO notes (note, user_ID, test_ID) VALUES (21, ?, ?)";
               
                                if($stmt = mysqli_prepare($link, $sql)){
                                    $param_eleve_id = $eleve_id;
                                    $param_eval_id = $test_id;
                         
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt,"ii", $param_eleve_id, $param_eval_id);
                                    
                                    // Set parameters
                                                // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        $cpt +=1;
                                    }
                                }
                    }
                }









                header("location: matiere.php?matiere=".$matiere."");
            } else{
                echo "Une erreur s'est produite";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une évaluation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ajouter une évaluation</h2>
        <p>Remplissez les champs suivants :</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($test_err)) ? 'has-error' : ''; ?>">
                <label>Nom de l'evaluation</label>
                <input type="text" name="test" class="form-control" value="<?php echo $test; ?>">
                <span class="help-block"><?php echo $test_err; ?></span>
                <label>Type de notation : </label>
                <select name="typeN">
                    <option value="0">Note</option>
                    <option value="1">Mention</option>
                </select>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="valider">
                <a href="matiere.php?matiere=<?php echo $matiere; ?>" class="btn btn-warning">retour</a>
            </div>
        </form>
    </div>    
</body>
</html>