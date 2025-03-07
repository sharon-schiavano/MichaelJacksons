<?php
session_start();
?>

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

    <header>
        <figure>
            <img src="../assets/images/logo/white-logo.png" alt="logo">
        </figure>
    </header>

    <main>


        <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] === true): ?>
            <section class="profile">
                <h2>Bentornato, <?php echo $_SESSION['username'] ?>!</h2>
                
                <div class="drag-drop-area" id="drop-area">
                    <?php
                    if (isset($_SESSION['id'])) {
                        require_once "../php_in_comune/config.php";
                        $user_id = $_SESSION['id'];

                        $query = "SELECT immagine_profilo FROM utenti WHERE id = $1";
                        $result = pg_prepare($db, "get_profile_image", $query);
                        $result = pg_execute($db, "get_profile_image", array($user_id));

                        if ($row = pg_fetch_assoc($result)) {
                            $imagePath = $row['immagine_profilo'];
                            $profileImage = (!empty($imagePath) && file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath))
                                ? $imagePath
                                : '../uploads/profile_images/default.jpg';
                            echo "<img src='$profileImage' id='profileImage' class='user-image'>";
                        }
                    }
                    ?>
                    <form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div id="img-view">
                            <p>Trascina o <span id="browse-link" style="color: blue; cursor: pointer;">clicca qui</span> per cambiare la foto</p>
                        </div>
                        <input type="file" id="input-file" accept="image/*" name="image" hidden>
                    </form>
                </div>
                <button type="button" id="deleteButton" class="delete-button">Elimina immagine</button>
            </section>
        <?php endif; ?>

        <section class="trending">
            <h2>Canzoni di Tendenza</h2>
            <div class="list">
                <?php
                $songs = [
                    ["billiejean.jpg", "Billie Jean", "Michael Jackson", "billie-jean.mp3"],
                    ["Thriller.jpg", "Thriller", "Michael Jackson", "thriller.mp3"],
                    ["offthewall.jpg", "Don't Stop 'Til You Get Enough", "Michael Jackson", "dont-stop-til-you-get-enough.mp3"]
                ];
                foreach ($songs as $song) {
                    echo "<div class='card'>
                            <img src='../assets/images/songs/$song[0]' alt='$song[1]'>
                            <h4>$song[1]</h4>
                            <p>$song[2]</p>
                            <audio controls>
                                <source src='../assets/audio/$song[3]' type='audio/mpeg'>
                                Il tuo browser non supporta l'elemento audio.
                            </audio>
                          </div>";
                }
                ?>
            </div>
        </section>

        <section class="trending">

            <h2>Articoli di Tendenza</h2>

            <div class="list">

                <div class="card">
                    <img src="../assets/images/shop/funkomj1.png" alt="Articolo 1">
                    <h4>FunkoPop! MJ</h4>
                    <p>20.00€</p>
                </div>

                <div class="card">
                    <img src="../assets/images/shop/mjtee.png" alt="Articolo 2">
                    <h4>Maglietta MJ</h4>
                    <p>20.00€</p>
                </div>

                <div class="card">
                    <img src="../assets/images/shop/bad.jpg" alt="Articolo 3">
                    <h4>Bad (Album)</h4>
                    <p>12.00€</p>
                </div>
            </div>
    <a href="../shop/index.php" class="shop-button">Vai allo shop</a>
</section>

        <section class="players">
            <h2>Migliori giocatori del mese</h2>
            <ul class="player-list">
                <?php
                include '../php_in_comune/config.php';

                if ($db) {
                    $query = "SELECT username FROM utenti ORDER BY mjc DESC LIMIT 5";
                    $result = pg_query($db, $query);

                    if ($result) {
                        $players = pg_fetch_all($result);
                        if ($players) {
                            foreach ($players as $index => $player) {
                                echo "<li>" . ($index + 1) . ". " . htmlspecialchars($player['username']) . "</li>";
                            }
                        } else {
                            echo "<li>Nessun giocatore disponibile</li>";
                        }
                    } else {
                        echo "<li>Errore nel caricamento della classifica</li>";
                    }
                    pg_close($db);
                } else {
                    echo "<li>Errore di connessione al database</li>";
                }
                ?>
            </ul>
            <a href="../classifica/index.php" class="read-more">Vai alla classifica</a>
        </section>

    </main>

    <?php include '../footer/footer.html'; ?>

    <script src="script.js"></script>

</body>

</html>