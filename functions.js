//toggle all checkboxes with class 'checkbox'
function toggleCheckBoxes() {
    //get all input types with class 'checkbox'
  checkboxes = document.getElementsByClassName('checkBox');
  //loop through each checkbox
  for(var i=0; i<checkboxes.length; i++) {
    //check if the checkbox if unchecked
    if(!checkboxes[i].checked){
      //check each checkbox
      checkboxes[i].checked = true;
    }
    else{
      //if it is checked uncheck it
      checkboxes[i].checked = false;
    }
    
  }
    
}

//redirect user to link
function redirect(link){
  window.location.replace(link);
}

//update values of each <select> box with their wedstrijd_id to the wedstrijd table
function submitValues(){
  //select all <select> elements with class text-input2
  selectboxes = document.getElementsByClassName('text-input2');
  
    //loop through each select box
    for(var i=0; i<selectboxes.length; i++) {
      //console.log(selectboxes);
      //update the scores of each wedstrijd through an AJAX function
      setScores(selectboxes[i].value, selectboxes[i].id);
    }
  //location.reload();

 
  
}

//gets the winner, score of player1 and player2 and updates them in database
function setScores(scores, wedstrijdId){
  //0 - 0 Win: Speler 1
    var winner = 0;
    var gelijkspel = 0;
    //get score 1 and score 2
    var score1 = scores[0];
    var score2 = scores[4];
    //check if the scores is equal match
    //0 - 0 Win: Speler 1 has 18 chars and is an equal match
    if(scores.length>18){
      //last character is the number of player that won
      gelijkspel= scores[18]
    }
    //check the scores to determine who won
    if(score1>score2){
      winner = 1;
    }
    else{
      //check if they did not play equal
      if(gelijkspel==0){
        winner = 2;
      }
      else{
          //referee decided who won with an equal game
          winner = gelijkspel;
      }
    }
    //update wedstrijd with scores and winner based on wedstrijd ID
    updateValues(score1,score2,winner,wedstrijdId);
}

//update wedstrijd with wedstrijdID with given parameters using a XMLHTTP request(AJAX)
function updateValues(score1, score2, winner, wedstrijdId){
    //XMLHttp variable can get and post data from/to pages
    var xhttp = new XMLHttpRequest();
     //open the API to upload the given data with a POST request
     xhttp.open("POST", "api/updateGame.php?w="+winner+"&&s1="+score1+"&&s2="+score2+"&&wID="+wedstrijdId);
      //send the request
      xhttp.send();
    //reload page
    
}
