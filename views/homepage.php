<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="public/css/main.css">
        <title>Homepage of Fun Games</title>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom:30px;">
                <a class="navbar-brand" href="?command=home">Fun Games</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup" style="text-align:right">
                    <div class="navbar-nav ms-auto">
                    <a class="nav-item nav-link active" href="?command=home">Home</a>
                    <a class="nav-item nav-link" href="?command=connections">Connections</a>
                    <a class="nav-item nav-link" href="?command=mastermind">Mastermind</a>
                    <a class="nav-item nav-link" href="?command=leaderboard">Leaderboard</a>
                    </div>
                </div>
            </nav>
        </header>

        <h1 style="margin-bottom:30px; text-align:center;">Explore!</h1>
        <div class="full-page-center">
            <div class="d-flex justify-content-center gap-3 flex-wrap" style="gap:30px;">
                <div class="card" style="width:18rem;" id="connections-card">
                    <img src="/fun-games/public/images/puzzle-pieces.png" class="card-img-top" alt="puzzle pieces">
                    <div class="card-body">
                        <h3>Play Connections</h3>
                    </div>
                </div>
                <div class="card" style="width:18rem;" id="connect4-card">
                    <img src="/fun-games/public/images/connect4.png" class="card-img-top" alt="connect four">
                    <div class="card-body">
                        <h3>Connect 4</h3>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
<script>
    document.getElementById('connections-card').addEventListener('click', () => {
        window.location.href = "?command=connections"; 
    }); 
    document.getElementById('connect4-card').addEventListener('click', () => {
        window.location.href = "?command=connect4"; 
    }); 
</script>