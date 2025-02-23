<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/favicon.ico" />
    <title>Michael Jackson's Alive</title>
  </head>
  <body>
    <span id="modalOverlay">
      <div id="startGameModal" class="modalMessage">
        <h3>Michael Jackson's Alive</h3>
        <p>Usa i tasti direzionali per giocare. Usando i comandi in alto puoi mettere in pausa/riprendere e riavviare il gioco. Puoi anche regolare l'audio dei suoni.</p>
        <button id="redirectButton">Continua</button>
      </div>
      <div id="endGameModal" class="modalMessage">
        <h3 id = "risultato">Grazie per aver giocato. Accedi con un account per ottenere MJC! <a href="../login-register/index.php">Registrati/Accedi</a></h3>
        <button id="playAgainButton">Gioca di nuovo</button>
      </div>
    </span>

    <?php include '../sidebar/sidebar.html'; ?>
    
    <div class="title">Michael Jackson's Alive</div>
    <section class="canvasArea">
      <div class="controls">
        <img id="pauseIcon" src="../assets/images/game/pause.png" title="Barra spaziatrice">
        <img id="restartIcon" src="../assets/images/game/restart.png">
        <img id="soundIcon" src="../assets/images/game/sound.png">
      </div>

      <label for="volume"></label>
      <input type="range" id="volume" min="0" max="1" step="0.01" value="0.5">

      <div id="score">Score: 0</div>
      <div class="combo">
        <p id="comboCount"></p>
        <span id="comboText">combo</span>
      </div>
      <canvas id="ttrCanvas" width="450" height="680"></canvas>
    </section>
    <div id="directions">
      <h4>Come giocare</h4>
      <ul>
        <li>Gioca usando le freccette direzionali</li>
        <li>Prova a far combaciare le frecce in movimento alle frecce statiche premendo le freccette direzionali nell'istante corretto.</li>
        <li>Un counter tiene conto dell'attuale combo/streak.</li>
        <li>Più alta è la combo, maggiori sono i punti che ricevi!</li>
        <li>Puoi mettere in pausa il gioco in qualsiasi momento premendo la barra spaziatrice o il tasto pausa in alto.</li>
      </ul>
      <button id="playButton1" style = "display: none ">Play Billie Jean</button>
      <button id="playButton2" style = "display: none ">Play Beat It</button>
      <button id="playButton3">Play Rock with You</button>
      <button id="playButton4" style = "display: none ">Play Smooth Criminal</button>
      <button id="playButton5" style = "display: none ">Play Thriller</button>

    </div>

    <div class="countdown-container">
      <div class="number" id="countdown"></div>
    </div>
    
    <div class="arrowImages">
      <img id="left" src="../assets/images/game/staticLeft.png">
      <img id="down" src="../assets/images/game/staticDown.png">
      <img id="up" src="../assets/images/game/staticUp.png">
      <img id="right" src="../assets/images/game/staticRight.png">
    </div>
    <audio id="mainSong" src="../assets/audio/billie-jean.mp3"></audio>
    <audio id="endApplause" src="../assets/audio/applause.mp3"></audio>
    <?php include '../footer/footer.html'; ?>
  </body>
  <script defer src="./js/arrowSprites.js" type="text/javascript"></script>
  <script defer src="./js/script.js" type="text/javascript"></script>
</html>