<?php
  //Ik doe dit zodat ik functies van functions.php kan gebruiken
  require "functions.php";

  
  //verbinding maken met Database voor gegevens
  try{
    $db = new PDO("mysql:host=localhost;dbname=toernooi;", "root","");
  }
  catch(PDOException $exception)
  {
    //echo elke fout met database
      echo $exception->getmessage();
  }
  
?>

<!--HTML-documenttype, taal, tekensets instellen om pagina te definiëren
 maak verbinding met functions_js.js om Javascript te gebruiken -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Homepagina</title>
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header met titel -->
<body>
  <center>
  <h2>Homepagina</h2>
    <!-- table met alle quicklinks -->
    <p><a href="spelerTV.php">Spelers toevoegen</a></p>
    <p><a href="spelerAP.php">Spelers aanpassen</a></p>
    <p><a href="schoolAM.php">School toevoegen</a></p>
    <p><a href="banen.php">Banen toevoegen</a></p>
    <p><a href="logout.php">logout</a></p>
</center>
  </body>
</html>