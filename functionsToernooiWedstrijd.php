<?php

//post 2 buttons
//1 with create toernooi and 1 with choose toernooi
function toernooiPage($db){
    //post form
    
    //post table to make a new toernooi 
    if(isset($_POST["make"]))
    {
        postMakeToernooi($db);
    }
    else{
        //button that makes new toernooi
        if(isset($_POST["toernooiSubmit"])){
            //get posted variables
            $toernooiname = $_POST["toernooiName"];
            $omschrijving = $_POST["omschrijving"];
            //check variables
            if(checkToernooi($toernooiname, $omschrijving, $db)){
                //insert toernooi
                insertToernooi($toernooiname, $omschrijving, $db);
                $toernooi_id = getIdFromToernooiName($toernooiname, $db);
                //make a table to assign spelers to a toernooi
                postAanmelden($toernooi_id, $db);

            }
        }
        else{
            //check if aanmelding button is pressed
            if(isset($_POST["meldAan"])){
                
                //check if there are checkboxes toggled
                if(isset($_POST["speler_id"])){
                //get ID's to upload into the aanmeldingen table
                        $toernooiId = $_POST["hiddenToernooi"];
                        $spelerIdArr = $_POST["speler_id"];
                        $succesfulQueries = 0;
                //put each speler into aanmeldingen 
                    foreach($spelerIdArr as $spelerId){
                        if(insertAanmelding($spelerId, $toernooiId, $db))
                        {
                            $succesfulQueries++;
                        }
                    }
                    //check if all queries went well
                    //amount of successful queries = the amount of selected spelerID's
                    if($succesfulQueries==count($spelerIdArr)){
                        ?><br><div class="box-terug"><h2> Toernooi is aangemaakt en spelers zijn aangemeld</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                    }
                    else{
                        ?><br><div class="box-terug"><h2> Er is iets fout gegaan :(</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                    }
                    //organize all wedstrijden in toernooi
                    makeWedstrijden($toernooiId,$db);
                    header("Location: toernooiInzien.php?t=".$toernooiId);
                }
                
            }
            else{
                //post table with buttons to make a new toernooi and to choose a toernooi?>
                <div class="box-table2">
                <form action="" method="post">
                        <br>
                        <input type="submit" name="make" class="submit-btn" value="Maak nieuw toernooi">
                        <br>
                        <br>
                        <div class="submit-btn3"><a href="toernooiInzien.php" class="whitetext">Kies Toernooi</a></div>
                        <br>
                        <br>
                </form>
                </div>
                <?php
            }
        }
    }

}

//post table to make toernooi
function postMakeToernooi($db){
    //first post a form to insert a toernooi
    ?>
    <div class="box-table2">
    <form method="post" action=""> 
        <h1>Toernooi naam:</h1>
        <input type="text" class="text-input" name="toernooiName">
        <br><br>
        <h1>Toernooi omschrijving:</h1>
        <textarea name="omschrijving" rows="5" cols="40"></textarea>
        <br><br>
        <input type="submit" class="submit-btn" name="toernooiSubmit" value="Maak toernooi">
        <br><br>
    </form>
    <?php
}

//check if toernooi variables are valid
function checkToernooi($toernooiname, $omschrijving, $db){

    //check if there's a name
    if(!$toernooiname){
        echo "<h2> Toernooi naam is niet opgegeven!</h2>";
        return false;
    }
    else{
        //check if toernooiname is correct length
        if(strlen($toernooiname)>50){
            ?><br><div class="box-terug"><h2> Toernooi naam is te lang! (max 50)</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
            return false;
        }
        else{
            //check if $omschrijving is correct length
            if(strlen($omschrijving)>100){
                ?><br><div class="box-terug"><h2> Omschrijving is te lang! (max 100)</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                return false;
            }
            else{
                //check if toernooi table contains $toernooiname
                if(TableHas("toernooi", $toernooiname, "toernooi_naam", $db)){
                    ?><br><div class="box-terug"><h2> Toernooi bestaat al, probeer een andere naam</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                    return false;
                }
                else{
                    return true;
                }

            }
        }
    }
}

//inserts $toernooiname and $omschrijving into toernooi table
function insertToernooi($toernooiname, $omschrijving, $db){
    $queryToernooi = $db->prepare("insert into toernooi set toernooi_naam=?, omschrijving=?");
    $queryToernooi->execute(array($toernooiname, $omschrijving));
}

//inserts $spelerId and $toernooiId into aanmeldingen table
function insertAanmelding($spelerId, $toernooiId, $db){
    $queryAanmelding = $db->prepare("insert into aanmeldingen set speler_id=?, toernooi_id=?");
    $result = $queryAanmelding->execute(array($spelerId, $toernooiId));
    //check if query went well
    if($result){
        return true;
    }else{
        return false;
    }
}
//gets ID from toernooi
function getIdFromToernooiName($name, $db)
{
  //query to select toernooi_id
  $querySelect = $db->prepare("select toernooi_id from toernooi where toernooi_naam=?");
  $querySelect->execute(array($name));
  //number of results from the query
  $rows = $querySelect->rowCount();
  //if there are rows that means  there is acolomn with given name
  if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
        $fetch= $querySelect->fetch(PDO::FETCH_ASSOC);
        $idRes = $fetch["toernooi_id"];
        return $idRes;
  }
  else{
    return "no toernooi";
  }

}

//get each speler and with a check box to assign speler to toernooi with an insert 
//into the aanmeldingen table
function postAanmelden($toernooiId, $db){
     //query to get each player with a school
     ?>
        <div class="box-players">
            <?php
            //post name, desc and date of toernooi
            $toernooi = getToernooiById($toernooiId, $db);
            ?>
            <h2> Toernooinaam: <?php echo $toernooi["toernooi_naam"];?></h2>
            <h2> Omschrijving: <?php echo $toernooi["omschrijving"];?></h2>
            <h2> Datum: <?php echo $toernooi["datum"];?></h2>
        </div>
        <div class="box-players">
         <form method="post" action="">
             <br>
             <!-- with this hidden ID I will use to upload the aanmeldingen-->
             <input type="hidden" value="<?php echo $toernooiId?>" name="hiddenToernooi">

            <input type="submit" name="meldAan" value="Meld gecheckte spelers aan" class="submit-btn">
            <br><br>
            <div class="submit-btn" onclick="toggleCheckBoxes()" style="user-select: none;">Selecteer alle spelers</div>
        </div>
             <?php
            $querySpeScho = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id");
            $querySpeScho->execute();
            foreach($querySpeScho as $player){
                //post form with player by post voornaam+tussen+achternaam and school
                ?>
                <div class="box-players">
                <h2> Naam: <?php echo $player["voornaam"]." ".$player["tussenvoegsel"]." ".$player["achternaam"];?></h2>
                <h2> School: <?php echo $player["schoolnaam"];?></h2>
                <input type="checkbox" name="speler_id[]" value="<?php echo $player["speler_id"]; ?>" class="checkBox"/>
                </div>
                <?php
            }
            ?>
            </form>
            <?php
}

//returns fetched result of toernooi with id $idToer
function getToernooiById($idToer, $db){
      //query to select toernooi_id
  $querySelect = $db->prepare("select * from toernooi where toernooi_id=?");
  $querySelect->execute(array($idToer));
  //number of results from the query
  $rows = $querySelect->rowCount();
  //if there are rows that means  there is acolomn with given name
  if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
        $fetch= $querySelect->fetch(PDO::FETCH_ASSOC);
        return $fetch;
  }
  else{
    return "no toernooi";
  }
}

//post a form where user can pick a toernooi
function chooseToernooi($link,$db){
    ?> <h1> Kies toernooi: </h1> <?php
    $link = $link."?t=";
    //query to select each toernooi
    $queryToernooi = $db->prepare("select * from toernooi");
    $queryToernooi->execute();
    foreach($queryToernooi as $toernooi){
        //post a div with $link with the toernooi_id
        ?>
        <div class="box-toernooien">
        <h2> Toernooi naam: <?php echo $toernooi["toernooi_naam"];?></h2>
        <h2> Omschrijving: <?php echo $toernooi["omschrijving"]; ?></h2>
        <h2> Datum: <?php echo $toernooi["datum"]; ?></h2>
        <div class="submit-btn2"><a style="margin-left:10%;" href="<?php echo $link.$toernooi["toernooi_id"];?>" class="whitetext">Kies Toernooi</a></div>
        </div>
        <?php
    }
}

//post toernooi table with a toernooiID of $idT
//
// Get max ronde from toernooi
// 
// for loop until max ronde 
//  get all wedstrijden with i'th ronde
//  post result in a <div>
function postToernooi($idT, $db){
     //also post a box for the winner of the tournament
     //query to get winner of tournament
     $queryGetWinner = $db->prepare("SELECT * FROM toernooi INNER JOIN spelers ON toernooi.winnaar_id = spelers.speler_id WHERE toernooi_id=?");
     $queryGetWinner->execute(array($idT));
     $fetchWinner = $queryGetWinner->fetch(PDO::FETCH_ASSOC);
     if($fetchWinner){
     ?>
         <h1> Winnaar toernooi: <?php echo $fetchWinner["voornaam"]." ".$fetchWinner["tussenvoegsel"]." ".$fetchWinner["achternaam"];?></h1> 
     <?php
     }
    //query to get highest round
    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
    $queryGetHighestRound->execute(array($idT));
    //fetch highest round
    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
    $highestRound = $fetchRound["ronde"];
    //check if there are any rounds, if not there are no games for this tournament
     if($highestRound){
        //loop over each round
        for($i=0;$i<$highestRound; $i++){
            //post div for each round
            ?>
            <div class="box-round">
                <div class="ronde"><h1> Ronde: <?php echo $i+1; ?> </h1>
            </div>
            <?php
            //get wedstrijden from i'th round
            $queryGetWedstrijden = $db->prepare("SELECT * FROM wedstrijd WHERE toernooi_id=? AND ronde=? ORDER BY winnaar_id ASC, baan ASC");
            //$i+1 because $i starts at 0
            $queryGetWedstrijden->execute(array($idT, $i+1));
            //post all wedstrijden
            foreach ($queryGetWedstrijden as $wedstrijd){
                $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
                $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
                $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);
                //4.2.1 get baan per wedstrijd
                $baan = getBaanByID($wedstrijd["baan"], $db);
                ?>
                <div class="box-wedstrijden2">
                <?php
                //check if speler 1 is a null value if it is, it is a free pass
                if($speler1){
                    //check if speler1 is winner then make his name green
                    if($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]){
                        ?>
                        <h3 class="green">  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                        <h3 class="green">School: <?php echo $speler1["schoolnaam"]; ?></h3><?php
                    }
                    else{
                            //if there is a winnaar then speler 2 is winnaar meaning this is the loser, make their name red
                        if($winnaar){
                            ?>
                            <h3 class="red"> <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                            <h3 class="red"> School: <?php echo $speler1["schoolnaam"]; ?></h3>
                            <?php
                        }
                        else{
                            ?>
                            <h3> <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                            <h3> School: <?php echo $speler1["schoolnaam"]; ?></h3>
                            <?php 
                        }
                    }
                }
                //if there is no speler it is a free pass
                else{
                ?>
                    <h3 style="color:blue; text-align: center;">  Bot</h3>
                <?php
                }
                ?>
                    <h1 class="tp1"> VS </h1>
                <?php
                //also do this for speler2
                if($speler2){
                    //check if speler 2 is winnaar then make their name green
                    if($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]){
                        ?>
                        <h3 class="green"><?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h3>
                        <h3 class="green">School: <?php echo $speler2["schoolnaam"]; ?></h3>
                        <?php
                    }
                    else{
                        //if there is a winnaar then speler 1 is winnaar meaning this is the loser, make their name red
                        if($winnaar){
                            ?>
                            <h3 class="red">  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h3>
                            <h3 class="red"> School:  <?php echo $speler2["schoolnaam"]; ?></h3>
                            <?php
                        }
                        else{
                    ?>
                        <h3>  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h3>
                        <h3> School:  <?php echo $speler2["schoolnaam"]; ?></h3>
                    <?php 
                        }
                    }
                }
                //if there is no speler its a free pass
                else{
                        ?>
                <h3 style="color:blue; text-align: center;"> Bot</h3>
                        <?php
                }

                //check if there is a winner, then post the winner
                if($winnaar){
                    ?>
                    <h3 class="green"> Winnaar: <?php echo $winnaar["voornaam"]." ".$winnaar["tussenvoegsel"]." ".$winnaar["achternaam"];?></h3>
                    <?php
                }
                else{
                    ?>
                    <h3 class="green"> Winnaar: Geen Winnaar</h3>
                    <?php
                }

                //check if the game has scores
                if($wedstrijd["score1"]>-1){
                    //post score
                    ?>
                    <h3 style="color: purple"> Score: <?php echo $wedstrijd["score1"]."-".$wedstrijd["score2"];?></h3>
                    <?php
                }
                //Game has no score
                else{
                    ?>
                        <h3 style="color: purple"> Score: No Score</h3> 
                    <?php
                    
                }
                //4.2.2 check if there's a baan
                if($baan){
                    //post baan
                    ?>
                    <h3 style="color: #4CAF50"> Baan: <?php echo $baan ?></h2> 
                <?php
                }
                else{
                    //no baan
                    ?>
                    <h3 style="color: #4CAF50"> Baan: Geen baan</h2> 
                <?php
                }
                ?>
                </div>
                <?php
            }
            ?>
            </div>
            <?php
            
        }
     }
     else{
         ?><div class="box-table">
         <h1> Er zijn geen wedstrijden voor dit toenooi </h1>
        </div><?php
     }

   
    

}

///------------------
/// WEDSTRIJDEM
///------------------

//organize wedstrijden from toernooi with id $toernooiId
function makeWedstrijden($toernooiId,$db){
    //get array for banen to use
    $baanArr = getTennisBanen($db);
    //get an array with all spelers and dummy spelers
    $spelerArr = getSpelerArr($toernooiId, $db);
    //get each 2 player from array
    //loop over array 
    // get i'th element
    // get i+1th element
    for($i=0; $i<count($spelerArr)-1; $i+=2){
        echo $spelerArr[$i]["voornaam"]." ".$spelerArr[$i+1]["voornaam"]."//";
        //make a wedstrijd
        insertIntoWedstrijdTable($toernooiId,$spelerArr[$i]["speler_id"], $spelerArr[$i+1]["speler_id"], $db, $baanArr);
    }

}

//insert a first round wedstrijd into the wedstrijd table with given parameters
function insertIntoWedstrijdTable($toernooiId, $speler1, $speler2, $db, $baanArr){
    
    //check if speler1 is a dummy
    if($speler1==0){
        $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler2_id=?, winnaar_id=?");
        //insert westrijd with $toernooiId, $speler2,  winner is speler 2
        $queryWed->execute(array($toernooiId,$speler2, $speler2));
    }
    else{
        //check if speler2 is a dummy
        if($speler2==0){
            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, winnaar_id=?");
            //insert westrijd with $toernooiId, $speler1, winner is speler 1
            $queryWed->execute(array($toernooiId,$speler1, $speler1));
        }
        else{
            //both spelers are not dummies
            //get next index of baanarray
            $baan = getNext($baanArr);
            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, speler2_id=?, baan=?");
            //3.1.4 insert westrijd with $toernooiId, $speler1, $speler2, no winner, with an id of baan
            $queryWed->execute(array($toernooiId, $speler1,$speler2, $baan["baan_id"]));
        }
    }

}

///---
// make an array
// Get each aanmelding with given toernooi id
// SELECT * FROM `aanmeldingen` where toernooi_id = ?
// Get each speler with id of result
// for each ($result){
// insert into array result of this -> (SELECT * FROM `spelers` where toernooi_id = $result["speler_id"])
// }
// Add dummies to make array complete of players
// either 2,4,8,16,32,64 or 128 as total count
function getSpelerArr($toernooiId, $db){

    $playerArr = array();
    $queryAanmelding = $db->prepare("SELECT * FROM `aanmeldingen` where toernooi_id = ".$toernooiId);
    $queryAanmelding->execute(array());
    foreach($queryAanmelding as $aanmelding){
        $querySpelers = $db->prepare("SELECT * FROM `spelers` where speler_id = ".$aanmelding["speler_id"]);
        $querySpelers->execute(array());
        $spelerFetch= $querySpelers->fetch(PDO::FETCH_ASSOC);
        array_push($playerArr, $spelerFetch);
    }
    
    $arrayWithDummies = getDummyArray($playerArr);
    return $arrayWithDummies;
}

//-------
//decide count of dummy array
//  start a count of 1, while the count is smaller than pArr.length
//  count*2
//make a dummy player
// { ["speler_id"]=> string(1) "0" ["voornaam"]=> string(5) "dummy " ["tussenvoegsel"]=> 
// string(6) "" ["achternaam"]=> string(4) "Dummy" ["school_id"]=> string(1) "test" }
//
//while the dummyArray<finalCnt
//insert dummy into dummyArray
// check if not 2 dummy's after each other otherwise you'll get Dummy vs Dummy that's stupid
function getDummyArray($pArr){

    $dummyArray = $pArr;
    $finalCnt = 2;
    while($finalCnt<count($pArr)){
        $finalCnt = $finalCnt*2;
    }

    $dummy = array(
        "speler_id"=>0,
        "voornaam"=>"Dummy",
        "tussenvoegsel"=>"",
        "achternaam"=>"freepass",
        "school_id"=>"test"
    );

    while(count($dummyArray)<$finalCnt){
        array_push($dummyArray, $dummy);
    }

    if($finalCnt<128){
        while(!isRandomDummy($dummyArray)){
            shuffle($dummyArray);
        }
    }
    else{
        shuffle($dummyArray);
    }
    return $dummyArray;
}

//-----
//checks if there are 2 dummies after each other in $arr
// check the previous player
// loop over every element in $arr
// get the speler_id
// check if speler_id is equal to element["speler_id"]
function isRandomDummy($arr){

    for($i=0; $i<count($arr)-1;$i+=2){

        if($arr[$i]["speler_id"]==$arr[$i+1]["speler_id"]){
            return false;
        }
    }
    return true;
}

//post the wedstrijden of current round with $toernooiId
function postWedstrijden($toernooiId, $db){
    //get the lowest round with no winner
    //this is the current round
    $queryCurrRound = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='".$toernooiId."' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC, baan ASC");
    $queryCurrRound->execute(array());

    //Query to post current round in <h1>Ronde</h1>
    $queryGetRonde = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='".$toernooiId."' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC");
    $queryGetRonde->execute(array()); 
    $fetchRonde = $queryGetRonde->fetch(PDO::FETCH_ASSOC);
    $rowsRonde = $queryGetRonde->rowCount();

    //only post update button if there are rounds left
    if($rowsRonde>0){
    //submit values from each <select> with this buttons
    ?> 
    <button onclick="submitValues();" class="submit-btn4"> Update Scores </button>
    <button class="submit-btn" style="padding:10px;" onclick="location.reload();">Next Round -></button>
    <?php 
    }

    //check if there are any rounds left
    if($rowsRonde>0)
    {
        ?>
        <h1>Ronde: <?php echo $fetchRonde["ronde"];?></h1>
        <!-- hidden ID to post with updated wedstrijd -->
        <?php
        $count = 0;
        $array = array();
        foreach($queryCurrRound as $wedstrijd){
            $count++;
            array_push($array,$wedstrijd);
            $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
            $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
            $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);
            //4.2.1 get baan per wedstrijd
            $baan = getBaanByID($wedstrijd["baan"], $db);
            //if there's a winaar make it dark so the user knows this game has been played already
            if(!$winnaar){
            ?>
            <div class="box-wedstrijden">
            <h2 class="green"> Ronde: <?php echo $wedstrijd["ronde"]; ?></h2>
            <?php
             
            }
            else{
                ?>
                <div class="box-wedstrijden-dark">
                <h2 class="green"> Ronde: <?php echo $wedstrijd["ronde"]; ?></h2>
                <?php  
            }
            //check if speler 1 is a null value if it is, it is a free pass
            if($speler1){
                //check if speler1 is winner then make his name green
                if($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]){
                    ?>
                    <h2 class="green"> <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h2>
                    <h2 class="green"> <?php echo $speler1["schoolnaam"]; ?></h2><?php
                }
                else{
                //if there is a winnaar then speler 2 is winnaar meaning this is the loser, make their name red
                    if($winnaar){
                        ?>
                        <h2 class="red">  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h2>
                        <h2 class="red"> School:  <?php echo $speler1["schoolnaam"]; ?></h2>
                        <?php
                    }
                    else{
                        ?>
                        <h2>  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h2>
                        <h2> School:  <?php echo $speler1["schoolnaam"]; ?></h2>
                        <?php 
                    }
                }
            }
            //if there is no speler it is a free pass
            else{
            ?>
                <h2 style="color:blue; text-align: center;">   Bot</h2>
            <?php
            }
            ?>
                <h1 class="tp1"> VS </h1>
            <?php
            //also do this for speler2
            if($speler2){
                //check if speler 2 is winnaar then make their name green
                if($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]){
                    ?>
                    <h2 class="green">  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h2>
                    <h2 class="green"> School:  <?php echo $speler2["schoolnaam"]; ?></h2>
                    <?php
                }
                else{
                    //if there is a winnaar then speler 1 is winnaar meaning this is the loser, make their name red
                    if($winnaar){
                        ?>
                        <h2 class="red">  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h2>
                        <h2 class="red"> School:  <?php echo $speler2["schoolnaam"]; ?></h2>
                        <?php
                    }
                    else{
                ?>
                    <h2>  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h2>
                    <h2> School:  <?php echo $speler2["schoolnaam"]; ?></h2>
                <?php 
                    }
                }
            }
            //if there is no speler its a free pass
            else{
                    ?>
            <h2 style="color:blue; text-align: center;">  Bot</h2>
                    <?php
            }
            //check if there is a winner, then post the winner
            if($winnaar){
                ?>
                <h2 class="green"> Winnaar: <?php echo $winnaar["voornaam"]." ".$winnaar["tussenvoegsel"]." ".$winnaar["achternaam"];?></h2>
                <?php
            }
            else{
                ?>
                <h2 class="green"> Winnaar: Geen Winnaar</h2>
                <?php
            }

            //check if the game has scores
            if($wedstrijd["score1"]>-1){
                //post score
                ?>
                <h2 style="color: purple"> Score: <?php echo $wedstrijd["score1"]."-".$wedstrijd["score2"];?></h2>
                <?php
            }
            //post a form where the user can choose a score (drop down menu)
            else{
                //check if there's a dummy
                if($speler1&&$speler2){
                    //post drop down menu with possible scores
                    ?>
                    <h2 style="color: purple"> Score: </h2>
                    <select name="score[]" class="text-input2" id="<?php echo $wedstrijd["wedstrijd_id"];?>">
                        <option> 3 - 0 </option>
                        <option> 0 - 3 </option>
                        <option> 1 - 2 </option>
                        <option> 2 - 1 </option>
                    </select>
                    <?php
                    
                }
                else{
                ?>
                    <h2 style="color: purple"> Score: No Score</h2> 
                <?php
                }
            }
            //4.1.2 check if there's a baan
            if($baan){
                //post baan
                ?>
                <h2 style="color: #4CAF50"> Baan: <?php echo $baan ?></h2> 
            <?php
            }
            else{
                //no baan
                ?>
                <h2 style="color: #4CAF50"> Baan: Geen baan</h2> 
            <?php
            }
            ?>
            </div>
            <?php
        }
    }
    //no rounds left
    else{
        //update rounds and make new wedstrijden
        updateRounds($toernooiId, $db);
        
    }
    

}

//make new wedstrijden with updated rounds
// get wedstrijden
// get max ronde
// new ronde = max ronde+1 
// put results of wedstrijden in array
// loop over array with for loop
// get i'th winner and i+1'th winner
// insert results into database
function updateRounds($toernooiId, $db){
    //3.2.1 get all banen
    $arrBanen = getTennisBanen($db);
    //get highest round
    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
    $queryGetHighestRound->execute(array($toernooiId));
    //fetch highest round
    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
    $newround = $fetchRound["ronde"]+1;

    //select wedstrijden with highest round
    $queryGetOldWedstrijden = $db->prepare("SELECT * FROM `wedstrijd` WHERE ronde = (SELECT MAX(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."') AND toernooi_id='".$toernooiId."'");
    $queryGetOldWedstrijden->execute();

    //var_dump($queryGetOldWedstrijden);
    $winnerArr = array();
    //put each result into array
    foreach($queryGetOldWedstrijden as $result){
        array_push($winnerArr, $result["winnaar_id"]);
    }
    
    //var_dump($winnerArr);
    //check if there is only one player left
    if(count($winnerArr)>1){
        //get pairs of winners
        for($i=0; $i<count($winnerArr)-1;$i+=2){
            $winner1 = $winnerArr[$i];
            $winner2 = $winnerArr[$i+1];
            //3.2.2 get next index from the banen
            $baan = getNext($arrBanen);
            //insert new round into database with $winner1, $winner2, $newround and $toernooiId
            $queryInsertWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=?, speler1_id=?, speler2_id=?, baan=?");
            //3.2.3 insert westrijd with $toernooiId, $newround, $winner1 as speler 1, $winner2 as speler 2, no winner, with an id of baan
            $queryInsertWed->execute(array($toernooiId, $newround, $winner1, $winner2, $baan["baan_id"]));
        }
        //reload page after insert
        ?><script> location.reload(); </script><?php
    }
    //no games left to play
    else{
        //check if there are wedstrijden
        if(count($winnerArr)>0){
        //get winner 
        $winnerTournament = $winnerArr[0];
        //set winner as tournament winner in tournament table
        finishTournament($winnerTournament, $toernooiId, $db);
        $winnaar = getSpelerByID($winnerTournament, $db);
        //post winnaar on page
        ?>
        <div class="box-table">
        <h1> Toernooi is afgelopen </h1>
        <h1 class="green"> Winnaar Toernooi: <?php echo $winnaar["voornaam"]." ".$winnaar["tussenvoegsel"]." ".$winnaar["achternaam"];?></h1>
        <h2 class="green"> School: <?php echo $winnaar["schoolnaam"];?></h2>
        </div>
        <?php
        }
        else{
            ?>
            <div class="box-table">
            <h1> Toernooi heeft geen wedstrijden </h1>
            </div>
            <?php
        }
    }
    

}

//set winID as winner of tournament and finish tournament
function finishTournament($winId, $tId, $db){
    //query to update tournament
    $queryFinishToernooi = $db->prepare("update toernooi set winnaar_id=?, afgesloten=1 WHERE toernooi_id=?");
    $queryFinishToernooi->execute(array($winId, $tId));

}

//3.1.1 return each baan in an array
function getTennisBanen($db){
    
    $baanArr = array();
    //query to get banen
    $queryBanen = $db->prepare("SELECT * FROM `banen`");
    $queryBanen->execute();
    //3.1.2 put each baan into array
    foreach($queryBanen as $baan){
        array_push($baanArr, $baan);
    }
    return $baanArr;
}

$arrCount = -1;
//3.1.3 gets next item from array, gets first item if at end of index
function getNext($arr){
    //add 1 to $arrCount (the index)
    $GLOBALS["arrCount"] = $GLOBALS["arrCount"]+1;
    //check if index is not too big
    if($GLOBALS["arrCount"]<=count($arr)-1){
        //return variable with index $arrCount of $arr
        $index = $GLOBALS["arrCount"];
        return $arr[$index];
    }else{
        //index is too big
        //reset the index($arrCount) to 0 and return 
        $GLOBALS["arrCount"] = 0;
        $index = $GLOBALS["arrCount"];
        return $arr[$index];
    }
}

//4.1.1 function to get baan by given Id
function getBaanByID($baanId, $db){
      //query to select baan with baanId
      $query_select = $db->prepare("SELECT* from banen where baan_id='".$baanId."'");
      $query_select->execute();
    //number of results from the query
    $rows = $query_select->rowCount();
    //if there are results that means  there is a colomn with given baan ID
    if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
        $baan= $query_select->fetch(PDO::FETCH_ASSOC);
        return $baan["baannaam"];
    }
    
}
