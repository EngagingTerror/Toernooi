<?php
  //Ik doe dit zodat ik functies uit functions.php en functionsToernooiWedstrijd.php kan gebruiken
  require "functions.php";
  require "functionsToernooiWedstrijd.php";

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

<!-- HTML-documenttype, taal, tekensets instellen om pagina te definiëren
 maak verbinding met functions_js.js om Javascript te gebruiken -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Wedstrijden</title>
  <script type="text/javascript" src="functions.js"></script>  
</head>
<!-- header met titel -->
<body>
<center>
    <p><a href="afgesloten.php">Homepagina</a></p>
        <h1 class="title">Wedstrijden</h1>
    <?php
    //haal de toernooi-ID uit de link
    if(isset($_REQUEST["t"])){
        $toernooiID = $_REQUEST["t"];
        postWedstrijden($toernooiID, $db);
    }
    else{
        chooseToernooi("wedstrijden.php",$db);
    }

    ?>
    </center>
  </body>
</html>