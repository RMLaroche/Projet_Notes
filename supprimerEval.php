<?php
require_once "config.php";
// Initialize the session
function listtests($link) {
            global $matiere;
            global $index;
            $sql = 'SELECT tests.libelle FROM tests
            LEFT JOIN matieres on matieres.ID_matiere = tests.matiere_ID 
            WHERE matieres.libelle = "'.$matiere.'"';
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $test = $row["libelle"];
                    echo "<div>
                        <input type='checkbox' id=".$index." name=".$index." value='".$test."'>
                        <label for=".$index.">".$test."</label>";
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
            } else {
                echo "Aucunes évaluations";
                echo "<br><br>";    
            }
        
}
session_start();

if (!empty($_GET["matiere"])){
$_SESSION["matiere"] = $_GET["matiere"];
//header("location: supprimerEval.php");
}
$matiere = $_SESSION["matiere"];

$username = $_SESSION["username"];
$index = 1;
$test_err = "";

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
/////////////////////////////////////////////////

 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 1 || $username_id != $matiere_id){
    header("location: login.php");
    exit;
}

 
 
 
// Define variables and initialize with empty values

 



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    global $index;

    $sql = 'SELECT count(*) FROM tests
            RIGHT JOIN matieres on matieres.ID_matiere = tests.matiere_ID 
            WHERE matieres.libelle = "'.$matiere.'"';

            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $size = $row["count(*)"];
                }
            }
    
    
    for ($i = 1; $i <= $size; $i++) {

        if(isset($_POST["$i"])){
            $test = $_POST["$i"];
            $sql = "DELETE FROM tests WHERE tests.libelle = ?";
        
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_test);
                
                // Set parameters
                $param_test = $test;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    $test = "";
                    $test_err = "";
                    header("supprimerEval.php?matiere=<?php echo $matiere; ?>");
                    // Check if matiere exists
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une évaluation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Supprimer une évaluation</h2>
            <br/>
            <h3>Evaluations</h3>
            <?php
                listtests($link);
            ?>
          
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Supprimer">
                <a href="matiere.php?matiere=<?php echo $matiere; ?>" class="btn btn-warning">retour</a>
            </div>
        </form>
    </div>    
</body>
</html>