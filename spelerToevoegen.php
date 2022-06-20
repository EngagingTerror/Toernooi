<?php
  //Ik doe dit zodat ik functies uit functions.php kan gebruiken
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
    
    $spelerCnt = getSpelerCnt($db);

?>

<!-- HTML-documenttype, taal, tekensets instellen om pagina te definiëren
 maak verbinding met functions_js.js om Javascript te gebruiken
 -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Spelers aanmelden</title>
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header met titel -->
<body>
<center>
    <p><a href="start.php">Homepagina</a></p>
    <h2>Spelers aanmelden</h2>
    <!-- tafel om spelers in te schrijven met formulier-->
    <?php 
      postRegistrateFormPlayer($db, $spelerCnt);
    ?>
</center>
  </body>
</html>