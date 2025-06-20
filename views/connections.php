<html>
    <?php 
        $json = file_get_contents('data/groups.json'); 
        if ($json === false){
            die('Error reading the json file'); 
        }
        // Decode the JSON file
        $json_data = json_decode($json, true); 
        if ($json_data === null){
            die('Error decoding the json file');
        }

        $word_list = []; 

        foreach($json_data as $n){
            foreach($n['words'] as $word){
                array_push($word_list, $word); 
            }
        }
        // shuffle($word_list); 
    ?>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="public/css/main.css">
        <title>Connections</title>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom:30px; padding-left:20px;">
                <a class="navbar-brand" href="?command=home">Fun Games</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup" style="text-align:right">
                    <div class="navbar-nav ms-auto">
                    <a class="nav-item nav-link active" href="?command=home">Home</a>
                    <a class="nav-item nav-link" href="?command=connections">Connections</a>
                    <a class="nav-item nav-link" href="?command=spotify">Spotify</a>
                    <a class="nav-item nav-link" href="?command=leaderboard">Leaderboard</a>
                    </div>
                </div>
            </nav>
        </header>
        <div class="game-container">
            <div id="popup">
                <p id="message"></p>
            </div>
            <div class="word-grid" id="grid-container"></div>

            <div class="options-container" >
                <p style="margin: 0;">Mistakes Remaining</p>
                <div id="mistake-dots"></div>
            </div>
            <div class="options-container">
                <button type="button" id="restart-btn" disabled>Restart</button>
                <button type="button" id="shuffle-btn">Shuffle</button>
                <button type="button" id="deselect-btn" disabled>Deselect All</button>
                <button type="button" id="submit-btn" disabled>Submit</button>
            </div>
        </div>
    </body>
</html>

<script>
    const groups = <?php echo json_encode($json_data); ?>;
    const rawWordList = <?php echo json_encode($word_list); ?>; 

    const selectedWords = new Set(); 

    let gameState, wordList, mistakesLeft, solvedGroups; 

    if (!localStorage.getItem('gameState')) {
        // First time --> shuffle word list and store it 
        gameState = {
            wordList: [...rawWordList].sort(() => 0.5 - Math.random()),
            mistakesLeft: 4,
            solvedGroups: []  // Each item: {group, level, words}
        };
        localStorage.setItem('gameState', JSON.stringify(gameState));  
    } else {
        gameState = JSON.parse(localStorage.getItem('gameState'));
    }
    wordList = gameState.wordList; 
    mistakesLeft = gameState.mistakesLeft; 
    solvedGroups = gameState.solvedGroups; 

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

    function clearAnswers() {
        selectedWords.clear(); 
        const selectedCards = document.querySelectorAll('.card.selected'); 
        for (const card of selectedCards){
            card.classList.remove('selected'); 
        }
        document.getElementById('submit-btn').disabled = true;
        document.getElementById('deselect-btn').disabled = true;
        // document.getElementById('restart-btn').disabled = true; 
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

                        if (solvedGroups.length == 4){
                            showPopup('You Win!'); 
                            // Enable restart button
                            document.getElementById('restart-btn').disabled = false;
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
    }

    displayCards(); 
</script>