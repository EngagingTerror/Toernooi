<?php
  //I do this so I can use functions from functions.php
  require "functions.php";

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
  <title>Speler gegevens aanpassen</title>
  <meta name="description" content="toernooi v0">
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header with title -->
<body class="back">
<center>
<div class="box-link">
      <a href="start.php"><h1 class="whitetext"> Homepagina </h1> </a>
    </div>
  <div class="holder">
  <h1 class="title">Speler gegevens aanpassen</h1>
    </div>
    <?php
    //post each player with school and name
    //and two buttons to edit or delete player
    postEachPlayerForm($db);
    ?>
</center>
  </body>
</html>