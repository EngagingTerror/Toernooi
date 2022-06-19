<?php

//posts each school from 'scholen' tablein as text
function postEachSchool($db){
    //query to get each school
    $query_schools= $db->prepare("SELECT * from scholen");
    $query_schools->execute();
    //post each schoolnaam from table
    foreach($query_schools as $school){
        ?><br><div class="white-box"><h2><?php echo $school["schoolnaam"];?></h2></div><?php
    }

}

//post a form to insert a given school into the database
function postRegistrateForm($db){
    //set up a post form
    ?>
    <br>
    <form method="post" action=""> 
        <h1>Schoolnaam:</h1>
        <input type="text" class="text-input" name="school-name">
        <br><br>
        <input type="submit" class="submit-btn" name="submit" value="Registreer school">
        <br><br>
    </form>
    <?php
    //check if schoolname is posted
    if(isset($_POST["submit"])){
        $school = $_POST["school-name"];
        //check if you can upload school to db
        checkValiditySchool($school, $db);
    
    }
}

//check if school is valid
function checkValiditySchool($school, $db){
    
    //check if string exists
    if(!$school){
        echo "<h2> Veld is niet gevuld!</h2>";
    }
    else{
        //check if string is not longer than max length in database
        if(strlen($school)>50){
            echo "<h2>Schoolnaam is te lang! Maximaal 50 karakters</h2>";
        }
        else{
            //check if schoolname already exists
            if(TableHas("scholen", $school, "schoolnaam",$db)){
                echo "<h2>School is al geregistreed!</h2>";
            }
            else{
                //I do this check because this is a name I use with organizing wedstrijden 
                if($school!="test"){
                    //zet school in scholen tabel
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
                else{
                    echo "<h2>Andere naam A.U.B.</h2>"; 
                }


            }
        }
    }
    
}

//checks if $colomn in $table database contains $needle
function TableHas($table, $needle, $colomn, $db)
{
  //query to select given needle from given colomn in user table
  $query_select = $db->prepare("select* from ".$table." where ".$colomn."='".$needle."'");
  $query_select->execute(array());
  //number of results from the query
  $rows = $query_select->rowCount();
  //if there are results that means  there is a $colomn with value $needle
  if($rows>0) {
    return true;
  }
  else{
    return false;
  }

}

//post form to registrate player
function postRegistrateFormPlayer($db,$splrcnt){
    //set up a post form
    ?>
    <br>
    <form method="post" action=""> 
        <h1>Voornaam:</h1>
        <input type="text" class="text-input2" name="voornaam">
        <h2>Tussenvoegsel:</h2>
        <input type="text" class="text-input3" name="tus">
        <h1>Achternaam:</h1>
        <input type="text" class="text-input2" name="achternaam">
        <h1>School:</h1>
        <?php
        //post a drop down box with all schools
                //query to get each school
                $query_schools= $db->prepare("SELECT * from scholen");
                $query_schools->execute();
        ?>

        <select name="school" class="text-input2">
            <?php
            //post each schoolname in an option 
            //we can use the from chosen school for the school_id in our insert query 
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
        //get ID because you can't use queries with only the name of the school we need the ID
        $school_id = getIdFromSchoolName($schoolnaam, $db);
        //check if the amount of spelers is smaller than 128
        if($splrcnt<128)
        {
            //check if given fields are valid
                if(checkValidityName($voornaam,$tus,$achternaam,$school_id,$db,1)){
                //insert data of speler into the database now it is valid
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

//check if voornaam, $tus and $achternaam are valid fields to upload to the database
function checkValidityName($voornaam,$tus,$achternaam,$school,$db,$registratie){
    //check if strings exists
    if(!$voornaam || !$achternaam){
        echo "<h2> Velden zijn niet gevuld!</h2>";
        return false;
    }
    else{
            //check if string is not longer than max length in database
            if(strlen($voornaam)>50||strlen($achternaam)>50||strlen($tus)>20){
                echo "<h2>Voornaam, tussenvoegsel of achternaam is te lang! Maximaal 50 karakters voor voor- en achternaam. Maximaal 20 voor tussenvoegsel</h2>";
                return false;
            }
            else{
                //check if speler is already registrated
                $query_select="";
                if(!$tus)
                {
                    //select speler from database without tussenvoegsel
                    $query_select = $db->prepare("select* from spelers where voornaam='".$voornaam."' and achternaam='".$achternaam."' and school_id='".$school."'");
                }
                else{
                    //select speler from database with tussenvoegsel
                    $query_select = $db->prepare("select* from spelers where voornaam='".$voornaam."' and achternaam='".$achternaam."' and tussenvoegsel='".$tus."' and school_id='".$school."'");
                }
                
                $query_select->execute();
                //var_dump($query_select);
                $rows = $query_select->rowCount();
                //check if there are any results
                if($rows>0) {
                    //check if you're registrating a player or not
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
  //if there are results that means  there is acolomn with given schoolname
  if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
        $school_id_fetch= $query_select->fetch(PDO::FETCH_ASSOC);
        $school_id = $school_id_fetch["school_id"];
        return $school_id;
  }
  else{
    return "no school";
  }

}


//gets amount of spelers in database
function getSpelerCnt($db){
    //query to get each player then get the amount of rows basically the amount of spelers
    $query_spelers= $db->prepare("SELECT * from spelers");
    $query_spelers->execute();
    $rows = $query_spelers->rowCount();
    return $rows;
}

//inserts $school into scholen table
function insertSchoolIntoDb($school,$db){

    //put school in scholen table
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

//post each player with school and name
//and two buttons to edit or delete player
function postEachPlayerForm($db){

    //if the wijzig button is pressed lead user to wijziging page with hiddenID
    if(isset($_POST["wijzig"])){
        //gets the hidden ID to select player with later on
        $hiddenId = $_POST["hiddenId"];
        postWijzigForm($hiddenId,$db);
    }
    else{
        //check if the verwijder button is pressed
        if(isset($_POST["verwijder"])){
            $hiddenId = $_POST["hiddenId"];
            deletePlayer($hiddenId, $db);
        }
        else{
            //check if the update button is pressed in postWijzigForm()
            if(isset($_POST["submit"])){
                //get each field 
                $speler_id = $_POST["speler_id"];
                $voornaam = $_POST["voornaam"];
                $tus = $_POST["tus"];
                $achternaam = $_POST["achternaam"];
                $schoolnaam = $_POST["school"];
                //get ID because you can't use queries with only the name of the school we need the ID
                $school_id = getIdFromSchoolName($schoolnaam, $db);
                //check if given fields are valid
                        if(checkValidityName($voornaam,$tus,$achternaam,$school_id,$db,0)){
                        //update data of speler into the database now it is valid with the ID given with this function
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
                    //post form with player by post voornaam+tussen+achternaam and school
                    ?>
                    <div class="box-players">
                    <form method="post" action=""> 
                    <h2> Naam: <?php echo $player["voornaam"]." ".$player["tussenvoegsel"]." ".$player["achternaam"];?></h2>
                    <h2> School: <?php echo $player["schoolnaam"];?></h2>
                    <!-- with this hidden ID I will use to delete/change the chosen player -->
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
    //get player with $id from table spelers
    $querySpelers = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where spelers.speler_id = ".$id);
    $querySpelers->execute();
    //fetch so only get 1 result
    $fetchedPlayer= $querySpelers->fetch(PDO::FETCH_ASSOC);
    //post a form to update speler with id $id from speler table

    ?>
    <div class="box-table">
    <br>
    <form method="post" action="">
        <input type="hidden" value="<?php echo $id;?>" name="speler_id">
        <h1> Nieuwe Voornaam:</h1>
        <input type="text" class="text-input2" name="voornaam" placeholder="<?php echo $fetchedPlayer["voornaam"];?>" value="<?php echo $fetchedPlayer["voornaam"];?>">
        <h2>Nieuwe Tussenvoegsel:</h2>
        <input type="text" class="text-input3" name="tus" placeholder="<?php echo $fetchedPlayer["tussenvoegsel"];?>" value="<?php echo $fetchedPlayer["tussenvoegsel"];?>">
        <h1>Nieuwe Achternaam:</h1>
        <input type="text" class="text-input2" name="achternaam" placeholder="<?php echo $fetchedPlayer["achternaam"];?>" value="<?php echo $fetchedPlayer["achternaam"];?>">
        <h1>Nieuwe School:</h1>
        <?php
        //post a drop down box with all schools
                //query to get each school
                $query_schools= $db->prepare("SELECT * from scholen");
                $query_schools->execute();
        ?>

        <select name="school" class="text-input2" placeholder="<?php echo $fetchedPlayer["schoolnaam"];?>" value="<?php echo $fetchedPlayer["schoolnaam"];?>">
            <?php
            //post each schoolname in an option 
            //we can use the from chosen school for the school_id in our insert query 
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

//delete speler with id of $speler_id from spelers tabel
function deletePlayer($speler_id, $db){

    $query_del = $db->prepare("DELETE FROM `spelers` WHERE `spelers`.`speler_id` = ?");
    $result = $query_del->execute(array($speler_id));
    //check if query was executed succesfully
    if($result){
        ?><br><div class="box-terug"><h2> Speler succesvol gedelete!</h2><br><a class="whitetext" href="spelerAanpassen.php"> Terug </a><br><br></div><?php
    }
    else{
        ?><br><div class="box-terug"><h2> Speler is aangemeld bij toernooi!</h2><br><a class="whitetext" href="spelerAanpassen.php"> Terug </a><br><br></div><?php
    }
}

//gets speler by $sId
function getSpelerByID($sId, $db){
      //query to select speler with schoolname
    $query_select = $db->prepare("SELECT spelers.*, schoolnaam FROM `spelers` INNER JOIN scholen ON spelers.school_id = scholen.school_id where speler_id='".$sId."'");
    $query_select->execute();
  //number of results from the query
  $rows = $query_select->rowCount();
  //if there are results that means  there is acolomn with given speler ID
  if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
        $speler= $query_select->fetch(PDO::FETCH_ASSOC);
        return $speler;
  }
}

//get id of speler 1 or 2 from wedstrijd table
function getSpelerIdGame($speler, $wedstrijdId, $db){

    //check if speler 1 or 2
    if($speler==1){
        //select speler1_id
        $query_select = $db->prepare("SELECT speler1_id FROM `wedstrijd` WHERE wedstrijd_id = ?");
        $query_select->execute(array($wedstrijdId));
        //number of results from the query
        $rows = $query_select->rowCount();
        //if there are results that means  there is acolomn with given speler ID
        if($rows>0) {
                //get the fetched result, this result will return an array instead of an PDO 
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
        //if there are results that means  there is acolomn with given speler ID
        if($rows>0) {
        //get the fetched result, this result will return an array instead of an PDO 
            $speler= $query_select->fetch(PDO::FETCH_ASSOC);
            return $speler["speler2_id"];
        }
        else{
            return "no result";
        }
    }
}

//post each baan in a div
function postEachBaan($db){
    //2.2.2 query to get each baan
    $query_baan= $db->prepare("SELECT * from banen");
    $query_baan->execute();
    //2.2.3 post each baannaam from table
    foreach($query_baan as $baan){
        ?><br><div class="white-box"><h2><?php echo $baan["baannaam"];?></h2></div><?php
    }
}

//post form to upload baan
function postRegistrateFormBaan($db){
        //2.3.2 set up a post form
        ?>
        <br>
        <form method="post" action=""> 
            <h1>Baan naam:</h1>
            <input type="text" class="text-input" name="baan-name">
            <br><br>
            <input type="submit" class="submit-btn" name="submit" value="Registreer baan">
            <br><br>
        </form>
        <?php
        //check if schoolname is posted
        if(isset($_POST["submit"])){
            
            $baan = $_POST["baan-name"];
            //check if you can upload school to db
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
        //check if string is not longer than max length in database
        if(strlen($baan)>20){
            echo "<h2>Baannaam is te lang! Maximaal 20 karakters</h2>";
        }
        else{
            //check if schoolname already exists
            if(TableHas("banen", $baan, "baannaam",$db)){
                echo "<h2>Baan is al geregistreed!</h2>";
            }
            else{
                    //baannaam is valid
                    //2.3.4 insert baan query
                    $query_insert_baan =  $db->prepare("INSERT INTO `banen` (`baannaam`) VALUES ('".$baan."')");
                    $result = $query_insert_baan->execute();
                    //check if query was succcesful
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

