<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
                    <div class="navbar-nav ml-auto">
                    <a class="nav-item nav-link active" href="?command=home">Home <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="?command=connections">Connections</a>
                    <a class="nav-item nav-link" href="?command=spotify">Spotify</a>
                    <a class="nav-item nav-link" href="?command=leaderboard">Leaderboard</a>
                    </div>
                </div>
            </nav>
        </header>

        <h1 class="centered-text" style="margin-bottom:30px;">Explore!</h1>
        <div class="full-page-center">
            <div class="d-flex justify-content-center gap-3 flex-wrap" style="gap:30px;">
                <div class="card custom-card" style="width:18rem;">
                    <img src="/fun-games/public/images/puzzle-pieces.png" class="card-img-top" alt="puzzle pieces">
                    <div class="card-body centered-text">
                        <h3>Play Connections</h3>
                    </div>
                </div>
                <div class="card custom-card" style="width:18rem;">
                    <img src="/fun-games/public/images/spotify-logo.png" class="card-img-top" alt="spotify green logo">
                    <div class="card-body centered-text">
                        <h3>Check your Spotify</h3>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>