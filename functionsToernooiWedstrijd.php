<?php

//post 2 knoppen
//1 met toernooi maken en 1 met toernooi kiezen
function toernooiPage($db){
    //post form
    
    //post table om een ​​nieuw toernooi te maken
    if(isset($_POST["make"]))
    {
        postMakeToernooi($db);
    }
    else{
        //knop die een nieuw toernooi maakt
        if(isset($_POST["toernooiSubmit"])){
            //get posted variables
            $toernooiname = $_POST["toernooiName"];
            $omschrijving = $_POST["omschrijving"];
            //check variables
            if(checkToernooi($toernooiname, $omschrijving, $db)){
                //insert toernooi
                insertToernooi($toernooiname, $omschrijving, $db);
                $toernooi_id = getIdFromToernooiName($toernooiname, $db);
                //maak een tafel om spelers toe te wijzen aan een toernooi
                postAanmelden($toernooi_id, $db);

            }
        }
        else{
            //controleer of de aanmeldingsknop is ingedrukt
            if(isset($_POST["meldAan"])){
                
                //controleer of er selectievakjes zijn ingeschakeld
                if(isset($_POST["speler_id"])){
                //ID's ophalen om te uploaden naar de aanmeldingstabel
                        $toernooiId = $_POST["hiddenToernooi"];
                        $spelerIdArr = $_POST["speler_id"];
                        $succesfulQueries = 0;
                //zet elke speler in aanmeldingen 
                    foreach($spelerIdArr as $spelerId){
                        if(insertAanmelding($spelerId, $toernooiId, $db))
                        {
                            $succesfulQueries++;
                        }
                    }
                    //controleer of alle vragen goed zijn gegaan
                    //aantal succesvolle queries = het aantal geselecteerde speler-ID's
                    if($succesfulQueries==count($spelerIdArr)){
                        ?><br><div class="box-terug"><h2> Toernooi is aangemaakt en spelers zijn aangemeld</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                    }
                    else{
                        ?><br><div class="box-terug"><h2> Er is iets fout gegaan :(</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                    }
                    //organiseer alle wedstrijden in toernooi
                    makeWedstrijden($toernooiId,$db);
                    header("Location: toernooiInzien.php?t=".$toernooiId);
                }
                
            }
            else{
                //post table met knoppen om een ​​nieuw toernooi te maken en een toernooi te kiezen
                ?>
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

//post table om een ​​toernooi te maken
function postMakeToernooi($db){
    //post eerst een form om een ​​toernooi in te voegenpost eerst een formulier om een ​​toernooi in te voegen
    ?>
    <div class="box-table2">
    <form method="post" action=""> 
        <h2>Toernooi naam:<input type="text" class="text-input" name="toernooiName"></h2>
        <br><br>
        <h2>Toernooi omschrijving</h2>
        <textarea name="omschrijving" rows="5" cols="40"></textarea>
        <br><br>
        <input type="submit" class="submit-btn" name="toernooiSubmit" value="Maak toernooi">
        <br><br>
    </form>
    <?php
}

//controleer of toernooivariabelen geldig zijn
function checkToernooi($toernooiname, $omschrijving, $db){

    //kijk of er een naam is
    if(!$toernooiname){
        echo "<h2> Toernooi naam is niet opgegeven!</h2>";
        return false;
    }
    else{
        //controleer of toernooinaam de juiste lengte heeft
        if(strlen($toernooiname)>50){
            ?><br><div class="box-terug"><h2> Toernooi naam is te lang! (max 50)</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
            return false;
        }
        else{
            //controleer of $omschrijving de juiste lengte heeft
            if(strlen($omschrijving)>100){
                ?><br><div class="box-terug"><h2> Omschrijving is te lang! (max 100)</h2><br><a class="whitetext" href="toernooi.php"> Terug </a><br><br></div><?php
                return false;
            }
            else{
                //controleer of toernooitabel $toernooiname bevat
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

//voegt $toernooinaam en $omschrijving toe aan toernooitabel
function insertToernooi($toernooiname, $omschrijving, $db){
    $queryToernooi = $db->prepare("insert into toernooi set toernooi_naam=?, omschrijving=?");
    $queryToernooi->execute(array($toernooiname, $omschrijving));
}

//voegt $spelerId en $toernooiId toe aan aanmeldingstabel
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
//krijgt ID van toernooi
function getIdFromToernooiName($name, $db)
{
  //vraag om toernooi_id te selecteren
  $querySelect = $db->prepare("select toernooi_id from toernooi where toernooi_naam=?");
  $querySelect->execute(array($name));
  //aantal resultaten van de query
  $rows = $querySelect->rowCount();
  //als er rijen zijn, betekent dit dat er een kolom is met de opgegeven naam
  if($rows>0) {
        //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO 
        $fetch= $querySelect->fetch(PDO::FETCH_ASSOC);
        $idRes = $fetch["toernooi_id"];
        return $idRes;
  }
  else{
    return "no toernooi";
  }

}

//krijg elke speler en met een selectievakje om speler toe te wijzen aan toernooi met een insert
//naar de aanmeldingen tafel
function postAanmelden($toernooiId, $db){
     //query om elke speler met een school te krijgen
     ?>
        <div class="box-players">
            <?php
            //post naam, beschrijving en datum toernooi
            $toernooi = getToernooiById($toernooiId, $db);
            ?>
            <h2> Toernooinaam: <?php echo $toernooi["toernooi_naam"];?></h2>
            <h2> Omschrijving: <?php echo $toernooi["omschrijving"];?></h2>
            <h2> Datum: <?php echo $toernooi["datum"];?></h2>
        </div>
        <div class="box-players">
         <form method="post" action="">
             <br>
             <!-- met deze verborgen ID zal ik gebruiken om de aanmeldingen te uploaden-->
             <input type="hidden" value="<?php echo $toernooiId?>" name="hiddenToernooi">

            <input type="submit" name="meldAan" value="Meld gecheckte spelers aan" class="submit-btn">
            <br><br>
            <div class="submit-btn" onclick="toggleCheckBoxes()" style="user-select: none;">Selecteer alle spelers</div>
        </div>
             <?php
            $querySpeScho = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id");
            $querySpeScho->execute();
            foreach($querySpeScho as $player){
                //post formulier met speler per post voornaam+tussen+achternaam en school
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

//geeft het opgehaalde resultaat van het toernooi terug met id $idToer
function getToernooiById($idToer, $db){
      //query om toernooi_id te selecteren
  $querySelect = $db->prepare("select * from toernooi where toernooi_id=?");
  $querySelect->execute(array($idToer));
  //aantal resultaten van de query
  $rows = $querySelect->rowCount();
  //als er rijen zijn, betekent dit dat er een kolom is met de opgegeven naam
  if($rows>0) {
        //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO 
        $fetch= $querySelect->fetch(PDO::FETCH_ASSOC);
        return $fetch;
  }
  else{
    return "no toernooi";
  }
}

//plaats een formulier waar de gebruiker een toernooi kan kiezen
function chooseToernooi($link,$db){
    ?> <h2> Kies toernooi: </h2> <?php
    $link = $link."?t=";
    //query om elk toernooi te selecteren
    $queryToernooi = $db->prepare("select * from toernooi");
    $queryToernooi->execute();
    foreach($queryToernooi as $toernooi){
        //post een div met $link met de toernooi_id
        ?>
        <div class="box-toernooien">
        <h3> Toernooi naam: <?php echo $toernooi["toernooi_naam"];?></h3>
        <h3> Omschrijving: <?php echo $toernooi["omschrijving"]; ?></h3>
        <h3> Datum: <?php echo $toernooi["datum"]; ?></h3>
        <div class="submit-btn2"><a href="<?php echo $link.$toernooi["toernooi_id"];?>" class="whitetext">Kies Toernooi</a></div>
        </div>
        <?php
    }
}

//post toernooitabel met een toernooiID van $idT
//
// Haal max ronde uit toernooi
//
// for loop tot max ronde
// haal alle wedstrijden met i'th ronde
// post resultaat in een <div>
function postToernooi($idT, $db){
     //post ook een box voor de winnaar van het toernooi
     //query om de winnaar van het toernooi te krijgen
     $queryGetWinner = $db->prepare("SELECT * FROM toernooi INNER JOIN spelers ON toernooi.winnaar_id = spelers.speler_id WHERE toernooi_id=?");
     $queryGetWinner->execute(array($idT));
     $fetchWinner = $queryGetWinner->fetch(PDO::FETCH_ASSOC);
     if($fetchWinner){
     ?>
         <h1> Winnaar toernooi: <?php echo $fetchWinner["voornaam"]." ".$fetchWinner["tussenvoegsel"]." ".$fetchWinner["achternaam"];?></h1> 
     <?php
     }
    //vraag om de hoogste ronde te krijgen
    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
    $queryGetHighestRound->execute(array($idT));
    //hoogste ronde halen
    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
    $highestRound = $fetchRound["ronde"];
    //controleer of er rondes zijn, zo niet, dan zijn er geen spellen voor dit toernooi
     if($highestRound){
        //lus over elke ronde
        for($i=0;$i<$highestRound; $i++){
            //post div voor elke ronde
            ?>
            <div class="box-round">
                <div class="ronde"><h1> Ronde: <?php echo $i+1; ?> </h1>
            </div>
            <?php
            //krijg wedstrijden vanaf de eerste ronde
            $queryGetWedstrijden = $db->prepare("SELECT * FROM wedstrijd WHERE toernooi_id=? AND ronde=? ORDER BY winnaar_id ASC, baan ASC");
            //$i+1 omdat $i begint bij 0
            $queryGetWedstrijden->execute(array($idT, $i+1));
            //post alle wedstrijden
            foreach ($queryGetWedstrijden as $wedstrijd){
                $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
                $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
                $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);
                //4.2.1 get baan per wedstrijd
                $baan = getBaanByID($wedstrijd["baan"], $db);
                ?>
                <div class="box-wedstrijden2">
                <?php
                //controleer of speler 1 een null-waarde is als dat zo is, is het een free pass
                if($speler1){
                    //check of speler1 winnaar is maak dan zijn naam groen
                    if($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]){
                        ?>
                        <h3 class="green">  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                        <h3 class="green">School: <?php echo $speler1["schoolnaam"]; ?></h3><?php
                    }
                    else{
                            //als er een winnaar is, dan is speler 2 winnaar, wat betekent dat dit de verliezer is, maak hun naam rood
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
                //als er geen speler is, is het een free pass
                else{
                ?>
                    <h3 style="color:blue; text-align: center;">  Bot</h3>
                <?php
                }
                ?>
                    <h1 class="tp1"> VS </h1>
                <?php
                //doe dit ook voor speler2
                if($speler2){
                    //controleer of speler 2 winnaar is en maak dan hun naam groen
                    if($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]){
                        ?>
                        <h3 class="green"><?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h3>
                        <h3 class="green">School: <?php echo $speler2["schoolnaam"]; ?></h3>
                        <?php
                    }
                    else{
                        //als er een winnaar is, dan is speler 1 winnaar, wat betekent dat dit de verliezer is, maak hun naam rood
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
                //als er geen speler is, is het een free pass
                else{
                        ?>
                <h3 style="color:blue; text-align: center;"> Bot</h3>
                        <?php
                }

                //check of er een winnaar is, post dan de winnaar
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

                //controleer of het spel scores heeft
                if($wedstrijd["score1"]>-1){
                    //post score
                    ?>
                    <h3 style="color: purple"> Score: <?php echo $wedstrijd["score1"]."-".$wedstrijd["score2"];?></h3>
                    <?php
                }
                //Spel heeft geen score
                else{
                    ?>
                        <h3 style="color: purple"> Score: No Score</h3> 
                    <?php
                    
                }
                //4.2.2 check of er een baan is
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

//organiseer wedstrijden van toernooi met id $toernooiId
function makeWedstrijden($toernooiId,$db){
    //krijg array voor banen om te gebruiken
    $baanArr = getTennisBanen($db);
    //krijg een array met alle spelers en dummy spelers
    $spelerArr = getSpelerArr($toernooiId, $db);
    //haal elke 2 speler uit de array
    //loop over array
    // krijg ik het element
    // krijg i+1e element
    for($i=0; $i<count($spelerArr)-1; $i+=2){
        echo $spelerArr[$i]["voornaam"]." ".$spelerArr[$i+1]["voornaam"]."//";
        //maak een wedstrijd
        insertIntoWedstrijdTable($toernooiId,$spelerArr[$i]["speler_id"], $spelerArr[$i+1]["speler_id"], $db, $baanArr);
    }

}

//voeg een eerste ronde wedstrijd in de wedstrijdtabel in met gegeven parameters
function insertIntoWedstrijdTable($toernooiId, $speler1, $speler2, $db, $baanArr){
    
    //check of speler1 een dummy is
    if($speler1==0){
        $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler2_id=?, winnaar_id=?");
        //voeg westrijd in met $toernooiId, $speler2, winnaar is speler 2
        $queryWed->execute(array($toernooiId,$speler2, $speler2));
    }
    else{
        //check of speler2 een dummy is
        if($speler2==0){
            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, winnaar_id=?");
            //voeg westrijd in met $toernooiId, $speler1, winnaar is speler 1
            $queryWed->execute(array($toernooiId,$speler1, $speler1));
        }
        else{
            //beide spelers zijn geen dummies
            //haal volgende index van baanarray
            $baan = getNext($baanArr);
            $queryWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=1, speler1_id=?, speler2_id=?, baan=?");
            //3.1.4 voeg westrijd in met $toernooiId, $speler1, $speler2, geen winnaar, met een id van baan
            $queryWed->execute(array($toernooiId, $speler1,$speler2, $baan["baan_id"]));
        }
    }

}

///---
// maak een array
// Krijg elke aanmelding met opgegeven toernooi-id
// SELECT * FROM `aanmeldingen` waar toernooi_id = ?
// Krijg elke speler met id van resultaat
// voor elke ($resultaat){
// insert in array resultaat van dit -> (SELECT * FROM `spelers` waar toernooi_id = $result["speler_id"])
// }
// Voeg dummies toe om de array compleet te maken met spelers
// ofwel 2,4,8,16,32,64 of 128 als totaal aantal
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
//bepaal het aantal dummy-arrays
// begin een telling van 1, terwijl de telling kleiner is dan pArr.length
// tel*2
// maak een dummy speler
// { ["speler_id"]=> string(1) "0" ["voornaam"]=> string(5) "dummy" ["tussenvoegsel"]=>
// string(6) "" ["achternaam"]=> string(4) "Dummy" ["school_id"]=> string(1) "test" }
//
// terwijl de dummyArray <finalCnt
//voeg dummy in dummyArray in
// controleer of er niet 2 dummy's achter elkaar zijn, anders krijg je Dummy vs Dummy, dat is dom
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
//controleert of er 2 dummies achter elkaar staan ​​in $arr
// controleer de vorige speler
// loop over elk element in $arr
// verkrijg de speler_id
// controleer of speler_id gelijk is aan element["speler_id"]
function isRandomDummy($arr){

    for($i=0; $i<count($arr)-1;$i+=2){

        if($arr[$i]["speler_id"]==$arr[$i+1]["speler_id"]){
            return false;
        }
    }
    return true;
}

//post de wedstrijden van de huidige ronde met $toernooiId
function postWedstrijden($toernooiId, $db){
    //haal de laagste ronde zonder winnaar
    //dit is de huidige ronde
    $queryCurrRound = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='".$toernooiId."' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC, baan ASC");
    $queryCurrRound->execute(array());

    //Query om huidige ronde te posten in <h1>Ronde</h1>
    $queryGetRonde = $db->prepare("SELECT * FROM `wedstrijd` WHERE toernooi_id='".$toernooiId."' 
    AND ronde = (SELECT MIN(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."' AND winnaar_id IS NULL) ORDER BY `wedstrijd`.`winnaar_id` ASC");
    $queryGetRonde->execute(array()); 
    $fetchRonde = $queryGetRonde->fetch(PDO::FETCH_ASSOC);
    $rowsRonde = $queryGetRonde->rowCount();

    //alleen post update-knop als er rondes over zijn
    if($rowsRonde>0){
    //verzend waarden van elke <select> met deze knoppen
    ?> 
    <button onclick="submitValues();" class="submit-btn4"> Update Scores </button>
    <button class="submit-btn" style="padding:10px;" onclick="location.reload();">Next Round -></button>
    <?php 
    }

    //check of er nog rondes over zijn
    if($rowsRonde>0)
    {
        ?>
        <h1>Ronde: <?php echo $fetchRonde["ronde"];?></h1>
        <!-- hidden ID om te posten met bijgewerkte wedstrijd -->
        <?php
        $count = 0;
        $array = array();
        foreach($queryCurrRound as $wedstrijd){
            $count++;
            array_push($array,$wedstrijd);
            $speler1 = getSpelerByID($wedstrijd["speler1_id"], $db);
            $speler2 = getSpelerByID($wedstrijd["speler2_id"], $db);
            $winnaar = getSpelerByID($wedstrijd["winnaar_id"], $db);
            //4.2.1 krijg baan per wedstrijd
            $baan = getBaanByID($wedstrijd["baan"], $db);
            //als er een winaar is, maak het dan donker zodat de gebruiker weet dat dit spel al gespeeld is
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
            //controleer of speler 1 een null-waarde is als dat zo is, is het een free pass
            if($speler1){
                //check of speler1 winnaar is maak dan zijn naam groen
                if($wedstrijd["winnaar_id"] == $wedstrijd["speler1_id"]){
                    ?>
                    <h3 class="green"> <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                    <h3 class="green"> <?php echo $speler1["schoolnaam"]; ?></h3><?php
                }
                else{
                //als er een winnaar is, dan is speler 2 winnaar, wat betekent dat dit de verliezer is, maak hun naam rood
                    if($winnaar){
                        ?>
                        <h3 class="red">  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                        <h3 class="red"> School:  <?php echo $speler1["schoolnaam"]; ?></h3>
                        <?php
                    }
                    else{
                        ?>
                        <h3>  <?php echo $speler1["voornaam"]." ".$speler1["tussenvoegsel"]." ".$speler1["achternaam"];?></h3>
                        <h3> School:  <?php echo $speler1["schoolnaam"]; ?></h3>
                        <?php 
                    }
                }
            }
            //als er geen speler is, is het een free pass
            else{
            ?>
                <h3 style="color:blue; text-align: center;">   Bot</h3>
            <?php
            }
            ?>
                <h2 class="tp1"> VS </h2>
            <?php
            //doe dit ook voor speler2
            if($speler2){
                //controleer of speler 2 winnaar is en maak dan hun naam groen
                if($wedstrijd["winnaar_id"] == $wedstrijd["speler2_id"]){
                    ?>
                    <h3 class="green">  <?php echo $speler2["voornaam"]." ".$speler2["tussenvoegsel"]." ".$speler2["achternaam"];?></h3>
                    <h3 class="green"> School:  <?php echo $speler2["schoolnaam"]; ?></h3>
                    <?php
                }
                else{
                    //als er een winnaar is, dan is speler 1 winnaar, wat betekent dat dit de verliezer is, maak hun naam rood
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
            //als er geen speler is, is het een free pass
            else{
                    ?>
            <h3 style="color:blue; text-align: center;">  Bot</h3>
                    <?php
            }
            //check of er een winnaar is, post dan de winnaar
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

            //controleer of het spel scores heeft
            if($wedstrijd["score1"]>-1){
                //post score
                ?>
                <h3 style="color: purple"> Score: <?php echo $wedstrijd["score1"]."-".$wedstrijd["score2"];?></h3>
                <?php
            }
            //post een formulier waar de gebruiker een score kan kiezen (drop down menu)
            else{
                //controleer of er een dummy is
                if($speler1&&$speler2){
                    //post drop-down menu met mogelijke scores
                    ?>
                    <h3 style="color: purple"> Score: </h3>
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
                    <h3 style="color: purple"> Score: No Score</h3> 
                <?php
                }
            }
            //4.1.2 check of er een baan is
            if($baan){
                //post baan
                ?>
                <h3 style="color: #4CAF50"> Baan: <?php echo $baan ?></h3> 
            <?php
            }
            else{
                //no baan
                ?>
                <h3 style="color: #4CAF50"> Baan: Geen baan</h3> 
            <?php
            }
            ?>
            </div>
            <?php
        }
    }
    //no rounds left
    else{
        //rondes bijwerken en nieuwe wedstrijden maken
        updateRounds($toernooiId, $db);
        
    }
    

}

//maak nieuwe wedstrijden met bijgewerkte rondes
// krijg wedstrijden
// haal max ronde
// nieuwe ronde = max ronde+1
// zet resultaten van wedstrijden in de array
// loop over array met for loop
// haal de i'de winnaar en de i+1'de winnaar
// voeg resultaten in database in
function updateRounds($toernooiId, $db){
    //3.2.1 krijg alle banen
    $arrBanen = getTennisBanen($db);
    //hoogste ronde halen
    $queryGetHighestRound = $db->prepare("SELECT MAX(ronde) as ronde FROM wedstrijd WHERE toernooi_id=?");
    $queryGetHighestRound->execute(array($toernooiId));
    //hoogste ronde halen
    $fetchRound = $queryGetHighestRound->fetch(PDO::FETCH_ASSOC);
    $newround = $fetchRound["ronde"]+1;

    //selecteer wedstrijden met hoogste ronde
    $queryGetOldWedstrijden = $db->prepare("SELECT * FROM `wedstrijd` WHERE ronde = (SELECT MAX(ronde) FROM wedstrijd WHERE toernooi_id='".$toernooiId."') AND toernooi_id='".$toernooiId."'");
    $queryGetOldWedstrijden->execute();

    //var_dump($queryGetOldWedstrijden);
    $winnerArr = array();
    //zet elk resultaat in array
    foreach($queryGetOldWedstrijden as $result){
        array_push($winnerArr, $result["winnaar_id"]);
    }
    
    //var_dump($winnerArr);
    //controleer of er nog maar één speler over is
    if(count($winnerArr)>1){
        //krijg paren van winnaars
        for($i=0; $i<count($winnerArr)-1;$i+=2){
            $winner1 = $winnerArr[$i];
            $winner2 = $winnerArr[$i+1];
            //3.2.2 volgende index ophalen van de banen
            $baan = getNext($arrBanen);
            //voeg nieuwe ronde in database in met $winner1, $winner2, $newround en $toernooiId
            $queryInsertWed = $db->prepare("insert into wedstrijd set toernooi_id=?, ronde=?, speler1_id=?, speler2_id=?, baan=?");
            //3.2.3 voeg westrijd in met $toernooiId, $newround, $winner1 als speler 1, $winner2 als speler 2, geen winnaar, met een id van baan
            $queryInsertWed->execute(array($toernooiId, $newround, $winner1, $winner2, $baan["baan_id"]));
        }
        //reload page after insert
        ?><script> location.reload(); </script><?php
    }
    //no games left to play
    else{
        //check of er wedstrijden zijn
        if(count($winnerArr)>0){
        //get winner 
        $winnerTournament = $winnerArr[0];
        //stel winnaar in als toernooiwinnaar in toernooitabel
        finishTournament($winnerTournament, $toernooiId, $db);
        $winnaar = getSpelerByID($winnerTournament, $db);
        //post winnaar op pagina
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

//stel winID in als winnaar van toernooi en beëindig toernooi
function finishTournament($winId, $tId, $db){
    //vraag om toernooi te updaten
    $queryFinishToernooi = $db->prepare("update toernooi set winnaar_id=?, afgesloten=1 WHERE toernooi_id=?");
    $queryFinishToernooi->execute(array($winId, $tId));

}

//3.1.1 retourneer elke baan in een array
function getTennisBanen($db){
    
    $baanArr = array();
    //vraag om banen te krijgen
    $queryBanen = $db->prepare("SELECT * FROM `banen`");
    $queryBanen->execute();
    //3.1.2 zet elke baan in array
    foreach($queryBanen as $baan){
        array_push($baanArr, $baan);
    }
    return $baanArr;
}

$arrCount = -1;
//3.1.3 haalt het volgende item uit de array, krijgt het eerste item als aan het einde van de index
function getNext($arr){
    //add 1 to $arrCount (the index)
    $GLOBALS["arrCount"] = $GLOBALS["arrCount"]+1;
    //controleer of index niet te groot is
    if($GLOBALS["arrCount"]<=count($arr)-1){
        //return variabele met index $arrCount van $arr
        $index = $GLOBALS["arrCount"];
        return $arr[$index];
    }else{
        //index is te groot
        // reset de index ($arrCount) naar 0 en keer terug
        $GLOBALS["arrCount"] = 0;
        $index = $GLOBALS["arrCount"];
        return $arr[$index];
    }
}

//4.1.1 functie om baan te krijgen door gegeven Id
function getBaanByID($baanId, $db){
      //query baan selecteren met baanId
      $query_select = $db->prepare("SELECT* from banen where baan_id='".$baanId."'");
      $query_select->execute();
    //aantal resultaten van de query
    $rows = $query_select->rowCount();
    //als er resultaten zijn, betekent dit dat er een kolom is met een gegeven baan-ID
    if($rows>0) {
        //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO
        $baan= $query_select->fetch(PDO::FETCH_ASSOC);
        return $baan["baannaam"];
    }
    
}
