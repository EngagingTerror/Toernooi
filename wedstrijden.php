<?php
  //I do this so I can use functions from functions.php
  require "functions.php";
  require "functionsToernooiWedstrijd.php";

  //SET VARIABLES--------------------------------------
  
  //make connection to Database for data :D
  try{
    $db = new PDO("mysql:host=localhost;dbname=toernooi;", "root","");
  }
  catch(PDOException $exception)
  {
    //echo any error with database
      echo $exception->getmessage();
  }
  
  //-------------------------------------------------------
?>

<!-- set up HTML doctype, language, charsets to define page
 make connection to functions_js.js to use Javascript -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Wedstrijden</title>
  <script type="text/javascript" src="functions.js"></script>  
</head>
<!-- header with title -->
<body>
<center>
    <p><a href="afgesloten.php">Homepagina</a></p>
        <h1 class="title">Wedstrijden</h1>
    <?php
    //get the toernooi ID from link
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