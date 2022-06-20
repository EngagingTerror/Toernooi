<?php
  //Ik doe dit zodat ik functies uit functions.php kan gebruiken
  require "functions.php";
  require "functionsToernooiWedstrijd.php";

  
  //maak verbinding met Database voor gegevens
  try{
    $db = new PDO("mysql:host=localhost;dbname=toernooi;", "root","");
  }
  catch(PDOException $exception)
  {
    //echo elke fout met database
      echo $exception->getmessage();
  }
  
?>

<!-- HTML-documenttype, taal, tekensets instellen om pagina te bedenken
 maak verbinding met functies_js.js om Javascript te gebruiken -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Banen Registreren</title>
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header met titel -->
<body>
<center>
  <p><a href="start.php">Homepagina</a></p>
        <h2>Banen registreren</h2>
    <!-- table met geregistreerde scholen-->
      <br>
    <h2> Geregistreerde Banen</h2>
    <!-- 2.2.1 haal banen uit de database en plaats ze allemaal -->
    <?php
        postEachBaan($db);
    ?>
    <br>
    <!--2.3.1 table banen inschrijven -->
        <?php
        postRegistrateFormBaan($db);
        ?>
</center>
  </body>
</html>