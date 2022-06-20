//schakel alle selectievakjes in met klasse 'checkbox'
function toggleCheckBoxes() {
    //krijg alle invoertypen met klasse 'checkbox'
  checkboxes = document.getElementsByClassName('checkBox');
  //loop door elk checkbox
  for(var i=0; i<checkboxes.length; i++) {
    //controleer of het selectievakje is uitgeschakeld
    if(!checkboxes[i].checked){
      //controleer elk checkbox
      checkboxes[i].checked = true;
    }
    else{
      //als het is aangevinkt, vink het dan uit
      checkboxes[i].checked = false;
    }
    
  }
    
}

//gebruiker omleiden naar link
function redirect(link){
  window.location.replace(link);
}

//update waarden van elk <select> vak met hun wedstrijd_id naar de wedstrijdtabel
function submitValues(){
  //selecteer alle <select> elementen met class text-input2
  selectboxes = document.getElementsByClassName('text-input2');
  
    //loop door elke selectiebox
    for(var i=0; i<selectboxes.length; i++) {
      //console.log(selectievakjes);
      //update de scores van elke wedstrijd via een AJAX-functie
      setScores(selectboxes[i].value, selectboxes[i].id);
    }
  //location.reload();

 
  
}

//krijgt de winnaar, score van speler1 en speler2 en werkt deze bij in de database
function setScores(scores, wedstrijdId){
  //0 - 0 Win: Speler 1
    var winner = 0;
    var gelijkspel = 0;
    //get score 1 and score 2
    var score1 = scores[0];
    var score2 = scores[4];
    //controleer of de scores gelijk zijn aan elkaar
    //0 - 0 Win: Speler 1 heeft 18 karakters en is een gelijke match
    if(scores.length>18){
      //laatste karakter is het aantal spelers dat heeft gewonnen
      gelijkspel= scores[18]
    }
    //controleer de scores om te bepalen wie er heeft gewonnen
    if(score1>score2){
      winner = 1;
    }
    else{
      //controleer of ze niet gelijk speelden
      if(gelijkspel==0){
        winner = 2;
      }
      else{
          //scheidsrechter besliste wie er won met een gelijk spel
          winner = gelijkspel;
      }
    }
    //update wedstrijd met scores en winnaar op basis van wedstrijd ID
    updateValues(score1,score2,winner,wedstrijdId);
}

//update wedstrijd met wedstrijdID met opgegeven parameters met behulp van een XMLHTTP-verzoek (AJAX)
function updateValues(score1, score2, winner, wedstrijdId){
    //XMLHttp-variabele kan gegevens van/naar pagina's ophalen en posten
    var xhttp = new XMLHttpRequest();
     //open de API om de gegeven gegevens te uploaden met een POST-verzoek
     xhttp.open("POST", "api/updateGame.php?w="+winner+"&&s1="+score1+"&&s2="+score2+"&&wID="+wedstrijdId);
      //stuur het verzoek
      xhttp.send();
    //herlaad pagina
    
}
