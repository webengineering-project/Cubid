
function toggleEventForm() {
    var form = document.getElementById('event-form');
    //var container = document.querySelector('.event-container');
    //var button = document.querySelector('.event-button');
    var computedStyle = window.getComputedStyle(form);
    var body = document.body;
    setInitialSelectedGames();
    search();

    if (computedStyle.display === 'none' || computedStyle.display === '') {
        form.style.display = 'flex';
        //container.classList.add('open');
        //button.classList.add('open');
        body.style.overflow = 'hidden';


    } else {
        form.style.display = 'none';
        //container.classList.remove('open');
        //button.classList.remove('open');
        body.style.overflowY = 'scroll';

    }

}

function search() {
    var games = document.getElementsByName("gameLi");
    var inputField = document.getElementById('search_input');


    document.getElementById("selectedTags").value = ""
    for (var game of games) {

        if(  !gameAlreadySelected(game) && game.innerText.toLowerCase().includes(inputField.value.toLowerCase())){
            game.style.display = '';
        }else{
            game.style.display = 'none';
        }


    }
    var notHiddenGames = new Array();
    for (const game of games) {
        if(game.style.display != 'none'){
            notHiddenGames.push(game);
            //game.style.display = 'none';
        }

    }
    //showfive(notHiddenGames);

}


function selection(tagToChange = new HTMLElement()){
    if(tagToChange.id.includes("Selected")){
        tagToChange.style.display = 'none';
        document.getElementById(tagToChange.id.replace("Selected","Unselected")).style.display = '';


    }else if (tagToChange.id.includes("Unselected")){
        tagToChange.style.display = 'none';
        document.getElementById(tagToChange.id.replace("Unselected","Selected")).style.display = '';
    }
    document.getElementById("search_input").value = "";
    search();
}

/* function gameAlreadyOwned( game = new HTMLElement()){
    var ownedGames = document.getElementsByName('ownedGame');

    for (var gameO of ownedGames) {
        console.log(gameO.innerText);
        if (((gameO.innerText.toLowerCase() == game.innerText.toLowerCase())) ){
            document.getElementById(game.id.replace("Unselected","Selected")).style.display = '';

            return true;
        }

    }
    return false;
} */

function setInitialSelectedGames( ){
    var selectedGames = document.getElementsByName('gameSelected');
    var ownedGames = document.getElementsByName('ownedGame');
    for (const game of selectedGames) {


        for (var gameO of ownedGames) {

            if (((gameO.innerText.toLowerCase() == game.innerText.toLowerCase()))) {
                document.getElementById(game.id).style.display = '';

            }
        }
    }

}

function gameAlreadySelected( game = new HTMLElement()){
    var selectedGames = document.getElementsByName("gameSelected");

    for (var gameO of selectedGames) {

        if ((gameO.innerText.toLowerCase() == game.innerText.toLowerCase()) && gameO.style.display == ''){
            document.getElementById("selectedTags").value = document.getElementById("selectedTags").value+gameO.innerText+"0";
            console.log(document.getElementById("selectedTags").value);
            return true;
        }

    }
    return false;
}

function redirectToProfile(email) {
    var profileUrl = '/profile/?profile=' + email;
    window.location.href = profileUrl;
}

