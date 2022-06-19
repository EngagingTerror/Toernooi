<?php
//get functions, ../ because it has to travel back to /toernooi/
require "../functions.php";
require "../functionsToernooiWedstrijd.php";
//make connection with database
try{
    $db = new PDO("mysql:host=localhost;dbname=toernooi;", "root","");
}
catch(PDOException $exception)
{
//echo any error with connection
  echo $exception->getmessage();
}

//get the variables given in the link
//for example 
//api/uploadGame.php?w=1&&s1=1&&s2=1&&wId=315
//$_REQUEST["w"] is the 'w=' in the link
//so $_REQUEST["w"] = "1"
$winner = $_REQUEST["w"];
$score1 = $_REQUEST["s1"];
$score2 = $_REQUEST["s2"];
$wedstrijdId = $_REQUEST["wID"];
    //get spelers ID of winner
    //winner is speler 1
    if($winner == 1){
        //get ID of speler 1
        $winnerID = getSpelerIdGame(1,$wedstrijdId, $db);
    }
    //speler 2 won
    else{
        //get ID of speler 2
        $winnerID = getSpelerIdGame(2,$wedstrijdId, $db);
    }
    if($winnerID=="no result"){
        $winnerID=9;
    }
//query to update game
$queryUpdate = $db->prepare("update wedstrijd set 
score1=?,
score2=?,
winnaar_id=?
WHERE wedstrijd_id=?");
//execute winnaar
$result = $queryUpdate->execute(array($score1, $score2, $winnerID, $wedstrijdId));
if($result){
    echo "succes";
}
else{
    echo "fail";
}
?>

