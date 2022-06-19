<?php
  //I do this so I can use functions from functions.php
  require "functions.php";


  //make connection to Database for data :D
  try{
    $db = new PDO("mysql:host=localhost;dbname=toernooi;", "root","");
  }
  catch(PDOException $exception)
  {
    //echo any error with database
      echo $exception->getmessage();
  }
    //SET VARIABLES--------------------------------------
    $spelerCnt = getSpelerCnt($db);

  //-------------------------------------------------------
?>

<!-- set up HTML doctype, language, charsets to define page
 make connection to functions_js.js to use Javascript -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Spelers Registreren</title>
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
        <h1 class="title">Spelers registreren</h1>
    </div>
    <!-- table to registrate spelers with form-->
  <div class="box-schools">
        <?php 
        postRegistrateFormPlayer($db, $spelerCnt);
        ?>
  </div>
</center>
  </body>
</html>