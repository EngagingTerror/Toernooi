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
  <title>Homepagina</title>
  <meta name="description" content="toernooi v0">
  <script type="text/javascript" src="functions_js.js"></script>  
</head>
<!-- header with title -->
<body class="back">
  <center>
  <div class="holder">
  <h1 class="title">Homepagina</h1>
    </div>
    <!-- table with all quicklinks -->
  <div class="box-table2">
    <br>
    <div class="box-link">
      <a href="spelerToevoegen.php"><h1 class="whitetext"> Spelers toevoegen </h1> </a>
    </div>
    <br>
    <div class="box-link">
      <a href="spelerAanpassen.php"><h1 class="whitetext"> Spelers aanpassen </h1> </a>
    </div>
    <br>
    <div class="box-link">
      <a href="school.php"><h1 class="whitetext"> School toevoegen </h1> </a>
    </div>
    <br>
    <div class="box-link">
      <a href="toernooi.php"><h1 class="whitetext"> Toernooi </h1> </a>
    </div>
    <br>
    <div class="box-link">
      <a href="wedstrijden.php"><h1 class="whitetext"> Wedstrijd spelen </h1> </a>
    </div>
    <br>
    <!-- 1.1 Link to banen.php -->
    <div class="box-link">
      <a href="banen.php"><h1 class="whitetext"> Banen toevoegen </h1> </a>
    </div>
    <br>
    <div class="box-link">
      <a href="logout.php"><h1 class="whitetext"> logout </h1> </a>
    </div>
    <br>
  </div>
</center>
  </body>
</html>