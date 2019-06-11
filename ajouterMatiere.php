<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["permit"] !== 2){
    header("location: login.php");
    exit;
}
function getUserId($username ,$link) {
    $sql = "SELECT id FROM users
            WHERE username = '$username'";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return $row["id"];
                }
            } else {
                echo "0 resultats";
            }
    }

// Include config file
require_once "config.php";

// Check if the user is logged in, if not then redirect him to login page


 
// Define variables and initialize with empty values
$matiere = $prof = "";
$matiere_err = $prof_err= "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["matiere"]))){
        $matiere = "Entrez un nom de matière";
    } else{
        // Prepare a select statement
        $sql = "SELECT ID_matiere FROM matieres WHERE libelle = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_matiere);
            
            // Set parameters
            $param_matiere = trim($_POST["matiere"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $matiere_err = "Ce nom de matière est déjà pris";
                } else{
                    $matiere = trim($_POST["matiere"]);
                }
            } else{
                echo "Oops! Une erreur s'est produite !";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    
    // Validate permition
    if(($_POST["prof"])==""){
        $prof_err = "Rentrez un professeur"; 
        }   
        else{ 
        $prof = trim($_POST["prof"]);
    }
    
    // Check input errors before inserting in database
    if(empty($matiere_err) && empty($prof_err)){

       $prof_id = getUserId($prof,$link);


        
        // Prepare an insert statement
        $sql = "INSERT INTO matieres (libelle, user_ID) VALUES (?, ?)";



       
        if($stmt = mysqli_prepare($link, $sql)){
            $param_matiere = $matiere;
            $param_prof = $prof_id;
 
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"si", $param_matiere, $param_prof);
            
            // Set parameters
                        // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Ajout d'une matière</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ajouter une matière</h2>
        <p>Remplissez les champs suivants :</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($matiere_err)) ? 'has-error' : ''; ?>">
                <label>Matière</label>
                <input type="text" name="matiere" class="form-control" value="<?php echo $matiere; ?>">
                <span class="help-block"><?php echo $matiere_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($prof_err)) ? 'has-error' : ''; ?>">
                <label>Professeur</label>
                <input type="text" name="prof" class="form-control" value="<?php echo $prof; ?>">
                <span class="help-block"><?php echo $prof_err; ?></span>
            </div>   
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="admin.php" class="btn btn-warning">retour</a>
            </div>
        </form>
    </div>    
</body>
</html>