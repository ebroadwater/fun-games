<html>
    <?php 
        $json = file_get_contents('data/groups.json'); 
        if ($json === false){
            die('Error reading the json file'); 
        }
        $decoded = json_decode($json, true);
        if ($decoded === null) {
            die('Invalid JSON structure');
        }
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
                    <a class="nav-item nav-link" href="?command=connect4">Connect 4</a>
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
                <button type="button" id="shuffle-btn">Shuffle</button>
                <button type="button" id="deselect-btn" disabled>Deselect All</button>
                <button type="button" id="submit-btn" disabled>Submit</button>
            </div>
            <div class="options-container" id="win-screen">
                <button type="button" id="restart-btn" disabled>Restart</button>
                <button type="button" id="new-game-btn" disabled="false">New Game</button>
                <p id="all-played-msg"></p>
            </div>
        </div>
        <script>
            const allGames = <?= json_encode($decoded) ?>;
        </script>
        <script src="public/js/connections.js"></script>
    </body>
</html>