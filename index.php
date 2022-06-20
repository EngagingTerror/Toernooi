<?php
// Initialiseer de sessie
session_start();
 
// Controleer of de gebruiker al is ingelogd, zo ja, stuur hem dan door naar de start pagina
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: start.php");
    exit;
}
 
// Inclusief configuratiebestand
require_once "config.php";
 
// Definieer variabelen en initialiseer met lege waarden
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Formuliergegevens verwerken wanneer formulier wordt ingediend
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Controleer of gebruikersnaam leeg is
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Controleer of het wachtwoord leeg is
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Inloggegevens valideren
    if(empty($username_err) && empty($password_err)){
        // Een selectieverklaring voorbereiden
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variabelen aan de voorbereide instructie als parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Parameters instellen
            $param_username = trim($_POST["username"]);
            
            // Poging om de voorbereide instructie uit te voeren
            if($stmt->execute()){
                // Controleer of de gebruikersnaam bestaat, zo ja, verifieer dan het wachtwoord
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Wachtwoord is correct, dus start een nieuwe sessie
                            session_start();
                            
                            // Gegevens opslaan in sessievariabelen
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Gebruiker doorverwijzen naar welkomstpagina
                            header("location: start.php");
                        } else{
                            // Wachtwoord is niet geldig, geef een algemene foutmelding weer
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Gebruikersnaam bestaat niet, geef een algemene foutmelding weer
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Verklaring sluiten
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
    <title>Login</title>
</head>
<body>
    <center>
       <h2>Login</h2>
        <p>Welkom bij de KNLTB applicatie.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </center>
</body>
</html>