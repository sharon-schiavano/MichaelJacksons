<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJStillAlive</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>

<body>

    <?php include '../sidebar/sidebar.html'; ?>

    <div class="search-box">

        <input type="text" id="search" placeholder="Cerca..." onkeyup="searchMenu()">

    </div>

    <main>
        <h1 id="myH1">Home</h1>
        <section class="trending">
            <h2>Canzoni di Tendenza</h2>
            <div class="list">
                <div class="card">
                    <img src="../assets/images/songs/billiejean.jpg" alt="Billie Jean">
                    <h4>Billie Jean</h4>
                    <p>Michael Jackson</p>
                    <audio controls>
                        <source src="../assets/audio/billie-jean.mp3" type="audio/mpeg">
                        Il tuo browser non supporta l'elemento audio.
                    </audio>
                </div>
                <div class="card">
                    <img src="../assets/images/songs/Thriller.jpg" alt="Thriller">
                    <h4>Thriller</h4>
                    <p>Michael Jackson</p>
                    <audio controls>
                        <source src="../assets/audio/thriller.mp3" type="audio/mpeg">
                        Il tuo browser non supporta l'elemento audio.
                    </audio>
                </div>
                <div class="card">
                    <img src="../assets/images/songs/offthewall.jpg" alt="Don't Stop 'Til You Get Enough">
                    <h4>Don't Stop 'Til You Get Enough</h4>
                    <p>Michael Jackson</p>
                    <audio controls>
                        <source src="../assets/audio/dont-stop-til-you-get-enough.mp3" type="audio/mpeg">
                        Il tuo browser non supporta l'elemento audio.
                    </audio>
                </div>
            </div>
        </section>


        <section class="trending">
            <h2>Articoli di Tendenza</h2>
            <div class="list">
                <div class="card">
                    <img src="../images/article1.jpg" alt="Articolo 1">
                    <h4>Maglietta Michael Jackson</h4>
                    <p>25$</p>

                </div>
                <div class="card">
                    <img src="../images/article2.jpg" alt="Articolo 2">
                    <h4>Tazza Michael Jackson</h4>
                    <p>10$</p>
                </div>
                <div class="card">
                    <img src="../images/article3.jpg" alt="Articolo 3">
                    <h4>Poster Michael Jackson</h4>
                    <p>15$</p>
                </div>

            </div>
            <a href="#" class="read-more">Vai allo shop</a>
        </section>

        <section class="players">
            <h2>Migliori giocatori del mese</h2>
            <ul class="player-list">
                <li>1. Michael Jordan</li>
                <li>2. LeBron James</li>
                <li>3. Kobe Bryant</li>
                <li>4. Larry Bird</li>
                <li>5. Shaquille O'Neal</li>
            </ul>
            <a href="#" class="read-more">Vai alla classifica</a>
        </section>


    </main>



    <?php include '../footer/footer.html'; ?>

    <script>
        function searchMenu() {
            let input = document.getElementById("search").value.toLowerCase();
            let items = document.querySelectorAll(".card h4"); 

            items.forEach(item => {
                let text = item.textContent.toLowerCase();
                let card = item.closest(".card"); 
                if (text.includes(input)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>

</body>

</html>