<!DOCTYPE html>
<html lang="en">
<?php
if (isset($_POST["submit3"])) {

// Hier haalt de data van index.php, wanneer er op login geklikt word.
$userid = $_POST["userid"];
$pwd = $_POST["pwd"];

// functies voor login.
include "database/database.php";
include "login/login_db.php";
include "login/login_check.php";

$login = new LoginChecker($userid, $pwd);

//Error check linked to login_check.php
$login->loginUser();

}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
</head>
<body>
<center>
    <p>Welkom bij KNLTB medewerkers applicatie</p>
        <h4>Log in</h4>
        <form action="start.php" method="post">
            <input type="text" name="userid" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <br></br>
            <button type="submit" name="submit3">Login</button>
            <p>Nog geen account klik dan sign in</p>
            <p><a href="signin.php">Sign In</a></p>
        </form>
    </center>
</body>
</html>