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
        shuffle($word_list); 
    ?>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="public/css/main.css">
        <title>Connections</title>
    </head>
    <h1>CONNECTIONS</h1>
    <div class="container full-page-center">
        <div class="grid-container">
            <?php 
                // Display cards
                foreach ($word_list as $word){
                    echo '<div class="grid-item">'; 
                    echo '<div class="card custom-card h-100">'; 
                    echo "<h3>$word</h3></div></div>"; 
                }
            ?>
        </div>
    </div>
    <span class="options-container"><p>Mistakes Remaining</p></span>
    <div class="options-container">
        <button type="button" id="shuffle-btn">Shuffle</button>
        <button type="button" id="deselect-btn" disabled="true">Deselect All</button>
        <button type="button" id="submit-btn" disabled="true">Submit</button>
    </div>
</html>

<script>
    const groups = <?php echo json_encode($json_data); ?>;

    const items = document.getElementsByClassName('grid-item'); 
    const selectedWords = new Set(); 

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

            // Enable /disable deselect all buttom
            if (selectedWords.size > 0){
                document.getElementById('deselect-btn').disabled = false; 
            } else {
                document.getElementById('deselect-btn').disabled = true;
            }
            // Enable / disable submit button
            if (selectedWords.size == 4){
                document.getElementById('submit-btn').disabled = false;
            } else {
                document.getElementById('submit-btn').disabled = true;
            }
            // console.log([...selectedWords]); // output contents of selectedWords
        }); 
    }

    document.getElementById('deselect-btn').addEventListener('click', () => {
        clearAnswers(); 
    });

    // document.getElementById('shuffle-btn').addEventListener('click', () => {
    //     location.reload(); 
    // }); 

    document.getElementById('submit-btn').addEventListener('click', () => {
        // const chosen = Array.from(selectedWords); 
        // console.log("Submitting:", chosen);
        validateAnswers(); 
    }); 

    function clearAnswers() {
        selectedWords.clear(); 
        const selectedCards = document.querySelectorAll('.card.selected'); 
        for (const card of selectedCards){
            card.classList.remove('selected'); 
        }
    }

    function validateAnswers(){
        console.log(groups); 

        const chosen = Array.from(selectedWords);
        const selectedCards = document.querySelectorAll('.card.selected'); 
        console.log("Selected:", chosen);

        let count = 0; 

        for (const group of groups){
            var words = group.words; 

            var overlap = chosen.filter(word => words.includes(word)); 
            
            if (overlap.length == 4){
                console.log(`CORRECT! Group: ${group.group}`); 
                groupLevel = group.level; 
                selectedCards.forEach(element => {
                    element.classList.add(`${groupLevel}`); 
                });
                clearAnswers(); 
                break; 
            }
            else if (overlap.length == 3){
                console.log(`SO CLOSE`); 
                break; 
            }
        }
    }
</script>