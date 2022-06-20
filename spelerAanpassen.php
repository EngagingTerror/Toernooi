<?php
  
//Ik doe dit zodat ik functies van functions.php kan gebruiken
  require "functions.php";

  
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

<!-- HTML-documenttype, taal, tekensets instellen om pagina te definiëren
 maak verbinding met functions_js.js om Javascript te gebruiken -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Speler gegevens aanpassen</title>
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header met titel -->
<body>
<center>
<p><a href="start.php">Homepagina</a></p>
<p><a href="afgesloten.php">spelers aanmelding sluiten</a></p>
  <h2>Speler gegevens aanpassen</h2>
    </div>
    <?php
    //post elke speler met school en naam
    // en twee knoppen om speler te bewerken of te verwijderen
    postEachPlayerForm($db);
    ?>
</center>
  </body>
</html>