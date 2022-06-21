<?php
// Inclusief configuratiebestand
require_once "config.php";
 
// Definieer variabelen en initialiseer met lege waarden
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Formuliergegevens verwerken wanneer formulier wordt ingediend
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Gebruikersnaam valideren
    if(empty(trim($_POST["username"]))){
        $username_err = "Vul hier een username in.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username kan alleen letters, cijfers en underscores bevatten.";
    } else{
        // Een selectieverklaring voorbereiden
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variabelen aan de voorbereide instructie als parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Parameters instellen
            $param_username = trim($_POST["username"]);
            
            // Poging om de voorbereide instructie uit te voeren
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "dit username is al ingebruik.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Wachtwoord valideren
    if(empty(trim($_POST["password"]))){
        $password_err = "Vul hier een wachtwoord in.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Wachtwoord moet minimaal 6 tekens bevatten.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Bevestig wachtwoord bevestigen
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "confirm hier uw wachtwoord.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Controleer invoerfouten voordat u deze in de database invoegt
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Een invoegopdracht voorbereiden
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variabelen aan de voorbereide instructie als parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            // Parameters instellen
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creëert een wachtwoord-hash
            
            // Poging om de voorbereide instructie uit te voeren
            if($stmt->execute()){
                // Redirect to lndex page
                header("location: index.php");
            } else{
                echo "Oeps! Er is iets fout gegaan. Probeer het later opnieuw.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <center>
        <h2>Sign Up</h2>
        <p>Maak hier uw account aan.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span> 
        </div>
        <div class="form-group">
            <label>wachtwoord</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Confirm wachtwoord</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
                <input type="submit" class="btn btn-primary" value="Sign up">
            <p>Heeft u al een account? <a href="lndex.php">Login here</a>.</p>
        </form>
</center>    
</body>
</html>