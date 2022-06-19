<?php

if (isset($_POST["submit2"])) {

    // Hier maakt die de data van signin.php, wanneer er op signin geklikt word.
    $userid = $_POST["userid"];
    $pwd = $_POST["pwd"];
    $pwd2 = $_POST["pwd2"];
    $email = $_POST["email"];

    // functie voor sign up.
    include "database.php";
    include "signup_db.php";
    include "signup_check.php";
    //linked to signup_check.
    $signup = new SignupCheck($userid, $pwd, $pwd2, $email);

    //Error checker linked to signup_check.php
    $signup->signupUser();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>signup page</title>
</head>

<body>
    <?php
    if (isset($_POST["submit2"])) {
    ?>
    <center>
        <h4>Uw account is aan gemaakt.</h4>
        <p>Klik op inloggen om verder te gaan</p>
        <a href="start.php">Inloggen</a>
    </center>
    <?php
    } else {
    ?>
    <center>
        <h4>Sign up</h4>
        <p>Maak hier uw account aan</p>
        <form action="signin.php" method="post">
            <input type="text" name="userid" placeholder="Username">
            <input type="text" name="email" placeholder="Email adres">
            <input type="password" name="pwd" placeholder="Password">
            <input type="password" name="pwd2" placeholder="Repeat Password">
            <br></br>
            <button type="submit" name="submit2">Sign up</button>
        </form>
    </center>
</body>
    <?php
    }

    ?>




</body>

</html>