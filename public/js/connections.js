// Load or pick the current game
let currentGame, gameState, solvedGroups, mistakesLeft, wordList, selectedWords, groups, rawWordList; 

if (localStorage.getItem('win')) {
    document.getElementById('win-screen').style.display = 'inline-flex';
} else {
    document.getElementById('win-screen').style.display = 'none'; 
}

initializeGame(); 

document.getElementById('deselect-btn').addEventListener('click', () => {
    clearAnswers(); 
});

document.getElementById('shuffle-btn').addEventListener('click', () => {
    gameState.wordList = [...rawWordList].sort(() => 0.5 - Math.random()); 
    localStorage.setItem('gameState', JSON.stringify(gameState)); 
    location.reload(); 
}); 

document.getElementById('submit-btn').addEventListener('click', () => {
    validateAnswers(); 
}); 

document.getElementById('restart-btn').addEventListener('click', () => {
    restartGame(); 
}); 

document.getElementById('new-game-btn').addEventListener('click', () => {
    newGame(); 
});

function initializeGame(forceNewGame = false) {
    if (forceNewGame || !localStorage.getItem('currentGameId')){
        const playedGames = JSON.parse(localStorage.getItem('playedGames')) || [];  // Initialize to empty array if localStorage empty
        const lastGameId = parseInt(localStorage.getItem('currentGameId'));
        const unplayedGames = allGames
            .map((g, i) => i)
            .filter(i => !playedGames.includes(i) && i !== lastGameId);

        if (unplayedGames.length === 0){ // No more unplayable games
            updateButtonStates(); 
            return; 
        }

        const nextIndex = Math.floor(Math.random() * unplayedGames.length); 
        const nextGameId = unplayedGames[nextIndex];
        currentGame = allGames[nextGameId]; 
        
        localStorage.setItem('currentGameId', nextGameId);
        // localStorage.setItem('playedGames', JSON.stringify([...playedGames, nextGameId]));

        gameState = {
            wordList: [...currentGame.groups.flatMap(g => g.words)].sort(() => 0.5 - Math.random()),
            mistakesLeft: 4,
            solvedGroups: []  // Each item: {group, level, words}
        };
        localStorage.setItem('gameState', JSON.stringify(gameState));  
    }
    else {
        const gameId = parseInt(localStorage.getItem('currentGameId'));
        currentGame = allGames[gameId]; 
        if (!currentGame) {
            console.error("Invalid gameId or game data.");
            return;  // failsafe
        }
        gameState = JSON.parse(localStorage.getItem('gameState'));
    }

    if (gameState && currentGame) {
        setUpGame();
    } 
}

function setUpGame() {
    if (!currentGame) {
        console.error("No currentGame available.");
        return;
    }
    if (!gameState) {
        console.error("Missing gameState");
        return;
    }
    groups = currentGame.groups; 
    rawWordList = currentGame.groups.flatMap(g => g.words); 
    wordList = gameState.wordList; 
    mistakesLeft = gameState.mistakesLeft; 
    solvedGroups = gameState.solvedGroups; 
    selectedWords = new Set(); 

    updateButtonStates(); 
    displayCards(); 
} 

function updateButtonStates() {
    const restartBtn = document.getElementById('restart-btn');
    const newGameBtn = document.getElementById('new-game-btn');

    const playedGames = JSON.parse(localStorage.getItem('playedGames')) || [];
    const currentGameId = parseInt(localStorage.getItem('currentGameId'));

    const unplayedGames = allGames.map((_, i) => i).filter(i => !playedGames.includes(i) && i !== currentGameId);

    const gameCompleted = solvedGroups.length === 4 || mistakesLeft === 0;

    if (unplayedGames.length === 0){
        document.getElementById('all-played-msg').textContent = "You've played all of the games";
        newGameBtn.disabled = true; 
        restartBtn.disabled = false; 
    } else {
        document.getElementById('all-played-msg').textContent = "";
        newGameBtn.disabled = false; 
        restartBtn.disabled = true;
    }
}

function clearAnswers() {
    selectedWords.clear(); 
    const selectedCards = document.querySelectorAll('.card.selected'); 
    for (const card of selectedCards){
        card.classList.remove('selected'); 
    }
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('deselect-btn').disabled = true;
}

function addCardListeners() {
    const items = document.getElementsByClassName('grid-item'); 
    for (const item of items){
        item.addEventListener('click', () => {
            const card = item.querySelector('.card'); 
            const word = card.textContent.trim(); 

            // Only select card if there aren't already 4 selected 
            if (selectedWords.has(word)){
                card.classList.toggle('selected'); 
                selectedWords.delete(word); 
            } 
            else if (selectedWords.size < 4){
                card.classList.toggle('selected'); 
                selectedWords.add(word); 
            }
            // Enable /disable deselect all and submit button
            document.getElementById('deselect-btn').disabled = selectedWords.size === 0; 
            document.getElementById('submit-btn').disabled = selectedWords.size !== 4; 
        }); 
    }
}

function renderDots() {
    const dotContainer = document.getElementById('mistake-dots'); 
    dotContainer.innerHTML = ''; // Clear existing dots
    for (let i = 0; i < mistakesLeft; i++){
        const dot = document.createElement('span'); 
        dot.className = 'dot'; 
        dotContainer.appendChild(dot); 
    }
}

function displayCards() {
    const gridContainer = document.getElementById('grid-container'); 
    gridContainer.innerHTML = ''; 

    // Display solved groups
    for (const group of solvedGroups){
        const section = document.createElement('div'); 
        section.classList.add('solved-group'); 
        section.classList.add(`${group.level}`); 

        const title = document.createElement('h3'); 
        title.textContent = group.group; 
        section.appendChild(title); 

        const row = document.createElement('div'); 
        row.classList.add('solved-row');
        
        for (const word of group.words){
            const w = document.createElement('h4'); 
            w.innerHTML = word; 
            row.appendChild(w);  
        }

        section.appendChild(row); 
        gridContainer.appendChild(section); 
    }

    // Display remaining words 
    const remainingWords = getRemainingWords(); 
    const unsolvedGrid = document.createElement('div'); 
    unsolvedGrid.classList.add('unsolved-grid'); 

    for (const word of remainingWords){
        const card = createCard(word); 
        unsolvedGrid.appendChild(card); 
    }
    gridContainer.appendChild(unsolvedGrid); 

    // Re-add card listeners
    addCardListeners(); 
    renderDots(); 
}

function showPopup(message){
    const popup = document.getElementById('popup'); 
    const popupMessage = document.getElementById('message'); 
    popupMessage.innerHTML = message; 
    popup.classList.add('show'); 

    setTimeout(() => {
        popup.classList.remove('show'); 
    }, 1500);
}

function createCard(word) {
    const gridItem = document.createElement('div'); 
    gridItem.className = 'grid-item'; 

    const card = document.createElement('div'); 
    card.className = 'card h-100'; 
    card.innerHTML = `<h3>${word}</h3>`; 

    gridItem.appendChild(card); 
    return gridItem; 
}

function getRemainingWords() {
    const solvedWords = solvedGroups.flatMap(group => group.words); 
    return wordList.filter(word => !solvedWords.includes(word)); 
}

function validateAnswers() {
    const chosen = Array.from(selectedWords);
    let isFound = false; 
    const cards = document.querySelectorAll('.card.selected');

    // All selected cards bounce
    for (const card of cards) {
        card.classList.add('bounce');
    }

    setTimeout(() => {
        for (const group of groups){
            const overlap = chosen.filter(word => group.words.includes(word)); 
            
            if (overlap.length == 4){
                isFound = true; 

                // Animate selected cards out
                for (const card of cards) {
                    card.classList.remove('bounce'); 
                    card.classList.add('fade-out');
                }

                // Wait for animation to finish before updating DOM
                setTimeout(() => {
                    solvedGroups.push({
                        group: group.group, 
                        level: group.level, 
                        words: group.words
                    }); 
                    gameState.solvedGroups = solvedGroups; 
                    localStorage.setItem('gameState', JSON.stringify(gameState));
                    updateButtonStates(); 

                    if (solvedGroups.length == 4){
                        showPopup('You Win!');
                        localStorage.setItem('win', true); 
                        // Update playedGames 
                        const playedGames = JSON.parse(localStorage.getItem('playedGames')) || []; 
                        const gameId = parseInt(localStorage.getItem('currentGameId')); 
                        if (!playedGames.includes(gameId)){
                            playedGames.push(gameId); 
                            localStorage.setItem('playedGames', JSON.stringify(playedGames)); 
                        }
                        
                        // Show new game and restart buttons
                        document.getElementById('win-screen').style.display = 'inline-flex';  
                    }

                    clearAnswers(); 
                    displayCards(); 
                }, 400); // match CSS transition duration
                break; 
            }
            else if (overlap.length == 3){
                showPopup('One away!');  

                break; 
            }
        }

        if (!isFound){
            for (const card of cards){
                card.classList.remove('bounce'); 
                card.classList.add('shake'); 

                // Remove shake class after animation to allow replay
                setTimeout(() => {
                    card.classList.remove('shake');
                }, 300);
            }

            mistakesLeft -= 1; 
            gameState.mistakesLeft = mistakesLeft; 
            localStorage.setItem('gameState', JSON.stringify(gameState));
            renderDots(); 

            // No more guesses left -- game over 
            if (mistakesLeft == 0){ 
                showPopup("Next Time"); 
                updateButtonStates(); 

                for (group of groups){
                    if (!solvedGroups.some(g => g.group === group.group)){
                        solvedGroups.push({
                            group: group.group, 
                            level: group.level, 
                            words: group.words
                        }); 
                    }
                } 

                gameState.solvedGroups = solvedGroups; 
                localStorage.setItem('gameState', JSON.stringify(gameState));
                // Update playedGames 
                const playedGames = JSON.parse(localStorage.getItem('playedGames')) || []; 
                const gameId = parseInt(localStorage.getItem('currentGameId')); 
                if (!playedGames.includes(gameId)){
                    playedGames.push(gameId); 
                    localStorage.setItem('playedGames', JSON.stringify(playedGames)); 
                }

                clearAnswers(); 
                displayCards(); 
            }
        }
    }, 400); 
}

function restartGame() {
    clearAnswers(); 
    localStorage.clear(); 
    location.reload(); 

    updateButtonStates(); 
}

function newGame() {
    localStorage.removeItem('gameState'); 
    localStorage.removeItem('win'); 
    location.reload(); 
    initializeGame(true); 

    updateButtonStates(); 
}