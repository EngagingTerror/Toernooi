<?php



//post form om speler te registreren
function postRegistrateFormPlayer($db,$splrcnt){
    //set up a post form
    ?>
    <br>
    <form method="post" action=""> 
        <h2>Voornaam:<input type="text" class="text-input2" name="voornaam"></h1>
        <h3>Tussenvoegsel:<input type="text" class="text-input3" name="tus"></h2>
        <h2>Achternaam:<input type="text" class="text-input2" name="achternaam"></h1>
        <h2>School</h1>
        <?php
        //post een drop-down box met alle scholen
                //query om elke school te krijgen
                $query_schools= $db->prepare("SELECT * from scholen");
                $query_schools->execute();
        ?>

        <select name="school" class="text-input2">
            <?php
            //plaats elke schoolnaam in een optie
            // we kunnen de van gekozen school gebruiken voor de school_id in onze invoeg query
            foreach($query_schools as $school){
                    $schoolnaam = $school["schoolnaam"]; 
                 ?> <option> <?php echo $schoolnaam; ?></option>
                <?php
                }
                ?>
        </select>
        <br>
        <br>
        <input type="submit" class="submit-btn" name="submit" value="Registreer speler">
        <br><br>
    </form>
    <?php
    //check if btn is pressed
    if(isset($_POST["submit"])){
        //get each field 
        $voornaam = $_POST["voornaam"];
        $tus = $_POST["tus"];
        $achternaam = $_POST["achternaam"];
        $schoolnaam = $_POST["school"];
        //krijg ID omdat je geen zoekopdrachten kunt gebruiken met alleen de naam van de school, we hebben de ID nodig
        $school_id = getIdFromSchoolName($schoolnaam, $db);
        //controleer of het aantal spelers kleiner is dan 128
        if($splrcnt<128)
        {
            //controleer of bepaalde velden geldig zijn
                if(checkValidityName($voornaam,$tus,$achternaam,$school_id,$db,1)){
                //vul de gegevens van de speler in de database in nu het geldig is
                $query_speler =  $db->prepare("INSERT 
                INTO `spelers` set voornaam=?, tussenvoegsel=?, achternaam=?, school_id=?");
                $result = $query_speler->execute(array(
                    $voornaam,
                    $tus,
                    $achternaam,
                    $school_id
                    ));
                //check if query was succcesful
                if($result){
                    echo "<h2> Speler succesvol geregistreerd!</h2>";
                }else{
                    echo "<h2> Er is iets fout gegaan probeer het nog eens :( </h2>";
                }
            }
        }
        else{
            echo "<h2> Speler limiet is bereikt (128)</h2>";
            return false;
        }


    }
}

//controleer of voornaam, $tus en $achternaam geldige velden zijn om te uploaden naar de database
function checkValidityName($voornaam,$tus,$achternaam,$school,$db,$registratie){
    //controleer of strings bestaan
    if(!$voornaam || !$achternaam){
        echo "<h2> Velden zijn niet gevuld!</h2>";
        return false;
    }
    else{
            //controleer of de string niet langer is dan de maximale lengte in de database
            if(strlen($voornaam)>50||strlen($achternaam)>50||strlen($tus)>20){
                echo "<h2>Voornaam, tussenvoegsel of achternaam is te lang! Maximaal 50 karakters voor voor- en achternaam. Maximaal 20 voor tussenvoegsel</h2>";
                return false;
            }
            else{
                //controleer of speler al is geregistreerd
                $query_select="";
                if(!$tus)
                {
                    //selecteer speler uit database zonder tussenvoegsel
                    $query_select = $db->prepare("select* from spelers where voornaam='".$voornaam."' and achternaam='".$achternaam."' and school_id='".$school."'");
                }
                else{
                    //selecteer speler uit database met tussenvoegsel
                    $query_select = $db->prepare("select* from spelers where voornaam='".$voornaam."' and achternaam='".$achternaam."' and tussenvoegsel='".$tus."' and school_id='".$school."'");
                }
                
                $query_select->execute();
                //var_dump($query_select);
                $rows = $query_select->rowCount();
                //check if there are any results
                if($rows>0) {
                    //controleer of je een speler registreert of niet
                    if($registratie==1){
                        echo "<h2> Speler is al geregistreerd</h2>";
                        return false;
                    }
                    else{
                        return true;
                    }
                }
                else{
                    return true;
                }
                
            }
    }

    
}


//get school_id with given name
function getIdFromSchoolName($name, $db)
{
  //query to select school_id
  $query_select = $db->prepare("select school_id from scholen where schoolnaam='".$name."'");
  $query_select->execute();
  //number of results from the query
  $rows = $query_select->rowCount();
  //als er resultaten zijn, betekent dit dat er een kolom is met de gegeven schoolnaam
  if($rows>0) {
        //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO
        $school_id_fetch= $query_select->fetch(PDO::FETCH_ASSOC);
        $school_id = $school_id_fetch["school_id"];
        return $school_id;
  }
  else{
    return "no school";
  }

}




//post elke baan in een div
function postEachBaan($db){
    //2.2.2 query om elke baan te krijgen
    $query_baan= $db->prepare("SELECT * from banen");
    $query_baan->execute();
    //2.2.3 post elke baannaam van table
    foreach($query_baan as $baan){
        ?><br><div class="white-box"><h3><?php echo $baan["baannaam"];?></h3></div><?php
    }
}

//post form to upload baan
function postRegistrateFormBaan($db){
        //2.3.2 set up a post form
        ?>
        <br>
        <form method="post" action=""> 
            <h2>Baan naam:<input type="text" class="text-input" name="baan-name"></h2>
            <br><br>
            <input type="submit" class="submit-btn" name="submit" value="Registreer baan">
            <br><br>
        </form>
        <?php
        //controleer of schoolnaam is gepost
        if(isset($_POST["submit"])){
            
            $baan = $_POST["baan-name"];
            //controleer of je school kunt uploaden naar db
            checkValidityBaan($baan, $db);
        
        }
}

//2.3.3 check if baan is valid
function checkValidityBaan($baan, $db){
    //check if there is a baan
    if(!$baan){
        echo "<h2> Veld is niet gevuld!</h2>";
    }
    else{
        //controleer of de string niet langer is dan de maximale lengte in de database
        if(strlen($baan)>20){
            echo "<h2>Baannaam is te lang! Maximaal 20 karakters</h2>";
        }
        else{
            //controleer of schoolnaam al bestaat
            if(TableHas("banen", $baan, "baannaam",$db)){
                echo "<h2>Baan is al geregistreed!</h2>";
            }
            else{
                    //baannaam is valid
                    //2.3.4 insert baan query
                    $query_insert_baan =  $db->prepare("INSERT INTO `banen` (`baannaam`) VALUES ('".$baan."')");
                    $result = $query_insert_baan->execute();
                    //controleer of de query is gelukt
                    if($result){
                        echo "<h2> Baan succesvol geregistreerd!</h2>";
                        header("Refresh:0");
                    }else{
                        echo "<h2> Er is iets fout gegaan probeer het nog eens :( </h2>";
                    }
            }
        }
    }

}
//krijgt aantal spelers in database
function getSpelerCnt($db){
    //query om elke speler te krijgen, krijg dan het aantal rows in feite het aantal spelers
    $query_spelers= $db->prepare("SELECT * from spelers");
    $query_spelers->execute();
    $rows = $query_spelers->rowCount();
    return $rows;
}

//voegt $school toe aan scholen table
function insertSchoolIntoDb($school,$db){

    //zet school in scholen table
    $query_school_insert =  $db->prepare("INSERT INTO `scholen` (`schoolnaam`) VALUES ('".$school."')");
    //var_dump($query_school_insert);
    $result = $query_school_insert->execute();
    //check if query was succcesful
    if($result){
        echo "<h2> School succesvol geregistreerd!</h2>";
        header("Refresh:0");
    }else{
        echo "<h2> Er is iets fout gegaan probeer het nog eens :( </h2>";
    }
}

//post elke speler met school en naam
// en twee knoppen om speler te bewerken of te verwijderen
function postEachPlayerForm($db){

    //als de wijzig-knop wordt ingedrukt, leidt de gebruiker naar de wijzigingspagina met hiddenID
    if(isset($_POST["wijzig"])){
        //krijgt de hidden ID om later een speler te selecteren
        $hiddenId = $_POST["hiddenId"];
        postWijzigForm($hiddenId,$db);
    }
    else{
        //controleer of de verwijderknop is ingedrukt
        if(isset($_POST["verwijder"])){
            $hiddenId = $_POST["hiddenId"];
            deletePlayer($hiddenId, $db);
        }
        else{
            //controleer of de update-knop is ingedrukt in postWijzigForm()
            if(isset($_POST["submit"])){
                //get each field 
                $speler_id = $_POST["speler_id"];
                $voornaam = $_POST["voornaam"];
                $tus = $_POST["tus"];
                $achternaam = $_POST["achternaam"];
                $schoolnaam = $_POST["school"];
                //krijg ID omdat je geen zoekopdrachten kunt gebruiken met alleen de naam van de school, we hebben de ID nodig
                $school_id = getIdFromSchoolName($schoolnaam, $db);
                //check if given fields are valid
                        if(checkValidityName($voornaam,$tus,$achternaam,$school_id,$db,0)){
                        //update gegevens van speler in de database nu is het geldig met de ID gegeven met deze functie
                        $query_speler =  $db->prepare("UPDATE `spelers` set voornaam=?, tussenvoegsel=?, achternaam=?, school_id=? WHERE speler_id=?");
                        $query_speler->execute(array(
                            $voornaam,
                            $tus,
                            $achternaam,
                            $school_id,
                            $speler_id
                            ));
                        
                        $result = $query_speler->execute();
                        //check if query was succcesful
                        if($result){
                            ?><br><div class="box-terug"><h2> Speler succesvol geupdate!</h2><br><a class="whitetext" href="spelerAanpassen.php"> Terug </a><br><br></div><?php
                        }else{
                            echo "<h2> Er is iets fout gegaan probeer het nog eens!</h2>";
                        }
                    }
        
            }
            else{
                //query to get each player with a school
                $querySpeScho = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id");
                $querySpeScho->execute();
                foreach($querySpeScho as $player){
                    //post formulier met speler per post voornaam+tussen+achternaam en school
                    ?>
                    <div class="box-players">
                    <form method="post" action=""> 
                    <h3> Naam: <?php echo $player["voornaam"]." ".$player["tussenvoegsel"]." ".$player["achternaam"];?></h3>
                    <h3> School: <?php echo $player["schoolnaam"];?></h3>
                    <!-- met deze verborgen ID zal ik gebruiken om de gekozen speler te verwijderen/wijzigen -->
                    <input type="hidden" value="<?php echo $player["speler_id"];?>" name="hiddenId">
                    <input type="submit" name="wijzig" value="Wijzig" class="submit-btn2">
                    <input type="submit" name="verwijder" value="Verwijder" class="submit-btn2">
                    </form>
                    </div>
                    <?php
                }
            }
        }
    }

    
}



//post form to update speler
function postWijzigForm($id, $db){
    //krijg speler met $id van table spelers
    $querySpelers = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where spelers.speler_id = ".$id);
    $querySpelers->execute();
    //ophalen dus krijg maar 1 resultaat
    $fetchedPlayer= $querySpelers->fetch(PDO::FETCH_ASSOC);
    //post een form om speler te updaten met id $id van spelerstafel

    ?>
    <div class="box-table">
    <br>
    <form method="post" action="">
        <input type="hidden" value="<?php echo $id;?>" name="speler_id">
        <h1> Nieuwe Voornaam:<input type="text" class="text-input2" name="voornaam" placeholder="<?php echo $fetchedPlayer["voornaam"];?>" value="<?php echo $fetchedPlayer["voornaam"];?>"></h1>
        <h2>Nieuwe Tussenvoegsel:<input type="text" class="text-input3" name="tus" placeholder="<?php echo $fetchedPlayer["tussenvoegsel"];?>" value="<?php echo $fetchedPlayer["tussenvoegsel"];?>"></h2>
        <h1>Nieuwe Achternaam:<input type="text" class="text-input2" name="achternaam" placeholder="<?php echo $fetchedPlayer["achternaam"];?>" value="<?php echo $fetchedPlayer["achternaam"];?>"></h1>
        <h1>Nieuwe School</h1>
        <?php
        //post een drop-down box met alle scholen
                //query om elke school te krijgen
                $query_schools= $db->prepare("SELECT * from scholen");
                $query_schools->execute();
        ?>

        <select name="school" class="text-input2" placeholder="<?php echo $fetchedPlayer["schoolnaam"];?>" value="<?php echo $fetchedPlayer["schoolnaam"];?>">
            <?php
            //plaats elke schoolnaam in een optie
            // we kunnen de van gekozen school gebruiken voor de school_id in onze invoegquery
            foreach($query_schools as $school){
                    $schoolnaam = $school["schoolnaam"]; 
                ?> <option> <?php echo $schoolnaam; ?></option>
                <?php
                }
                ?>
        </select>
        <br>
        <br>
        <input type="submit" class="submit-btn" name="submit" value="Update speler">
        <br><br>
    </form>
    </div>
    <?php
    

}

//verwijder speler met id van $speler_id uit spelerstabel
function deletePlayer($speler_id, $db){

    $query_del = $db->prepare("DELETE FROM `spelers` WHERE `spelers`.`speler_id` = ?");
    $result = $query_del->execute(array($speler_id));
    //controleer of de query met succes is uitgevoerd
    if($result){
        ?><br><div class="box-terug"><h2> Speler succesvol gedelete!</h2><br><a class="whitetext" href="spelerAanpassen.php"> Terug </a><br><br></div><?php
    }
    else{
        ?><br><div class="box-terug"><h2> Speler is aangemeld bij toernooi!</h2><br><a class="whitetext" href="spelerAanpassen.php"> Terug </a><br><br></div><?php
    }
}

//gets speler by $sId
function getSpelerByID($sId, $db){
      //query om speler met schoolnaam te selecteren
    $query_select = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where speler_id='".$sId."'");
    $query_select->execute();
  //aantal resultaten van de query
  $rows = $query_select->rowCount();
  //als er resultaten zijn, betekent dit dat er een kolom is met een gegeven speler-ID
  if($rows>0) {
        // haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO
        $speler= $query_select->fetch(PDO::FETCH_ASSOC);
        return $speler;
  }
}

//krijg id van speler 1 of 2 van wedstrijd table
function getSpelerIdGame($speler, $wedstrijdId, $db){

    //check if speler 1 or 2
    if($speler==1){
        //select speler1_id
        $query_select = $db->prepare("SELECT speler1_id FROM `wedstrijd` WHERE wedstrijd_id = ?");
        $query_select->execute(array($wedstrijdId));
        //aantal resultaten van de query
        $rows = $query_select->rowCount();
        //als er resultaten zijn, betekent dit dat er een kolom is met een gegeven speler-ID
        if($rows>0) {
                //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO 
                $speler= $query_select->fetch(PDO::FETCH_ASSOC);
                return $speler["speler1_id"];
        }
        else{
            return "no result";
        }
    }
    //speler2
    else{
        //select speler2_id
        $query_select = $db->prepare("SELECT speler2_id FROM `wedstrijd` WHERE wedstrijd_id = ?");
        $query_select->execute(array($wedstrijdId));
        //number of results from the query
        $rows = $query_select->rowCount();
        //als er resultaten zijn, betekent dit dat er een kolom is met een gegeven speler-ID
        if($rows>0) {
        //haal het opgehaalde resultaat op, dit resultaat retourneert een array in plaats van een PDO
            $speler= $query_select->fetch(PDO::FETCH_ASSOC);
            return $speler["speler2_id"];
        }
        else{
            return "no result";
        }
    }
}

//posts elke school uit 'scholen' tablein als tekst
function postEachSchool($db){
    //vraag om elke school te krijgen
    $query_schools= $db->prepare("SELECT * from scholen");
    $query_schools->execute();
    //post elke schoolnaam van  table
    foreach($query_schools as $school){
        ?><br><div class="white-box"><h3><?php echo $school["schoolnaam"];?></h3></div><?php
    }

}

//post een form om een ​​bepaalde school in de database in te voegen
function postRegistrateForm($db){
    //zet op een post form
    ?>
    <br>
    <form method="post" action=""> 
        <h2>Schoolnaam:<input type="text" class="text-input" name="school-name"></h2>
        <br><br>
        <input type="submit" class="submit-btn" name="submit" value="Registreer school">
        <br><br>
    </form>
    <?php
    //controleer of schoolnaam is posted
    if(isset($_POST["submit"])){
        $school = $_POST["school-name"];
        //controleer of je school kunt uploaden naar db
        checkValiditySchool($school, $db);
    
    }
}

//controleer of school geldig is
function checkValiditySchool($school, $db){
    
    //controleer of string bestaat
    if(!$school){
        echo "<h2> Veld is niet gevuld!</h2>";
    }
    else{
        //controleer of de string niet langer is dan de maximale lengte in de database
        if(strlen($school)>50){
            echo "<h2>Schoolnaam is te lang! Maximaal 50 karakters</h2>";
        }
        else{
            //controleer of schoolnaam al bestaat
            if(TableHas("scholen", $school, "schoolnaam",$db)){
                echo "<h2>School is al geregistreed!</h2>";
            }
            else{
                //Ik doe deze controle omdat dit een naam is die ik gebruik bij het organiseren van wedstrijden
                if($school!="test"){
                    //zet school in scholen tabel
                    $query_school_insert =  $db->prepare("INSERT INTO `scholen` (`schoolnaam`) VALUES ('".$school."')");
                    //var_dump($query_school_insert);
                    
                    $result = $query_school_insert->execute();
                    //controleer of de query is gelukt
                    if($result){
                        echo "<h2> School succesvol geregistreerd!</h2>";
                        header("Refresh:0");
                    }else{
                        echo "<h2> Er is iets fout gegaan probeer het nog eens :( </h2>";
                    }
                }
                else{
                    echo "<h2>Andere naam A.U.B.</h2>"; 
                }


            }
        }
    }
    
}

//controleert of $colomn in $table database $needle bevat
function TableHas($table, $needle, $colomn, $db)
{
  //query om de gegeven naald uit de gegeven kolom in de gebruikerstabel te selecteren
  $query_select = $db->prepare("select* from ".$table." where ".$colomn."='".$needle."'");
  $query_select->execute(array());
  //aantal resultaten van de query
  $rows = $query_select->rowCount();
  //als er resultaten zijn, betekent dit dat er een $colomn is met de waarde $needle
  if($rows>0) {
    return true;
  }
  else{
    return false;
  }

}

