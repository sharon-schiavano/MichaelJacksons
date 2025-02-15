/*
* Il codice della pagina è strutturato nel seguente modo:
* 1) dichirazione delle variabili
* 2) "main"
* 3) listener 
* 4) funzioni
*/


////////////////////////////////////////////////////////////// DICHIARAZIONE DELLE VARIABILI //////////////////////////////////////////////////////////////
const canvas = document.getElementById("ttrCanvas");
const ctx = canvas.getContext("2d");
let modalOverlay = document.getElementById("modalOverlay");
let directions = document.getElementById("directions");
let playButton1 = document.getElementById("playButton1");
let playButton2 = document.getElementById("playButton2");
let playButton3 = document.getElementById("playButton3");
let playButton4 = document.getElementById("playButton4");
let playButton5 = document.getElementById("playButton5");
//let playButton6 = document.getElementById("playButton6");
//let playButton7 = document.getElementById("playButton7");
let mainSong = document.getElementById("mainSong");
let applause = document.getElementById("endApplause");
let startModal = document.getElementById("startGameModal");
let endModal = document.getElementById("endGameModal");
let scoreDisplay = document.getElementById("score");
let comboCount = document.getElementById("comboCount");
let comboText = document.getElementById("comboText");
let pauseIcon = document.getElementById("pauseIcon");
let soundIcon = document.getElementById("soundIcon");
soundIcon.style.pointerEvents = "none";
let restartIcon = document.getElementById("restartIcon");
let maxScore = 0;
let score = 0;
let combo = 0;
let pause = false;
let restart = false;
let ended = false;
let leftPressed = false;
let downPressed = false;
let upPressed = false;
let rightPressed = false;
let dirSty = window.getComputedStyle(directions).getPropertyValue("display");
let arrowArray = [];
let arrowDrawTimeout;
const volumeControl = document.getElementById('volume');
mainSong.volume = volumeControl.value;
const countdownElement = document.getElementById("countdown");
const message = document.getElementById("risultato");
const wowSound = new Audio("../assets/audio/Hee hee.mp3");
wowSound.volume = volumeControl.value;
const awesomeSound = new Audio("../assets/audio/awesome.mp3");
awesomeSound.volume = volumeControl.value;
const amazingSound = new Audio("../assets/audio/amazing.mp3");
amazingSound.volume = volumeControl.value;
countdownElement.classList.add("hidden");
canzoneSelezionata = -1;
connesso = 0;

const countdownNumbers = ["Pronto?", "3", "2", "1", "VIA!", ""];
let indice = 0;
const countdownSound = new Audio("../assets/audio/Jingle (Countdown) - Mario Kart 8.mp3");
countdownSound.volume = volumeControl.value;


////////////////////////////////////////////////////////////// MAIN //////////////////////////////////////////////////////////////
generateWallpaper();
window.onload = drawStaticArrows;
document.getElementById("redirectButton").onclick = popupDirections;
playButton1.onclick = () => gameStart(1);
playButton2.onclick = () => gameStart(2);
playButton3.onclick = () => gameStart(3);
playButton4.onclick = () => gameStart(4);
playButton5.onclick = () => gameStart(5);
//playButton6.onclick = () => gameStart(6);
//playButton7.onclick = () => gameStart(7);
document.getElementById("playAgainButton").onclick = playAgain;

pauseIcon.onclick = gamePause;
restartIcon.onclick = gameRestart;
document.getElementById("mainSong").onended = songEnd;
document.getElementById("endApplause").onended = gameEnd;
document.addEventListener("keydown", handleKeyPress);
document.addEventListener("keyup", handleKeyPress);

////////////////////////////////////////////////////////////// LISTENER //////////////////////////////////////////////////////////////

//regolatore del volume
volumeControl.addEventListener('input', function () {
  mainSong.volume = volumeControl.value;
  countdownSound.volume = volumeControl.value;
  applause.volume = volumeControl.value;
  wowSound.volume = volumeControl.value;
  amazingSound.volume = volumeControl.value;
  awesomeSound.volume = volumeControl.value;
});

//assicura che il regolatore del volume non venga influenzato dall'utilizzo delle freccette
document.addEventListener('keydown', (event) => {
  const key = event.key;
  if (key === "ArrowUp" || key === "ArrowDown" || key === "ArrowLeft" || key === "ArrowRight" || key === " ") {
    // Rimuovi il focus dal regolatore del volume
    volumeControl.blur();
  }
});

//se l'utente preme la barra spaziatrice il gioco viene bloccato
document.addEventListener('keydown', function (event) {
  // Verifica se il tasto premuto è la barra spaziatrice 
  if (event.key === " " && !isCountdownActive) {
    gamePause();
  }
});

// Esegui gamePause quando la pagina diventa non visibile
document.addEventListener('visibilitychange', function () {
  if (document.visibilityState === "hidden" && !isCountdownActive && !pause) {
    gamePause();
  }
});

////////////////////////////////////////////////////////////// FUNZIONI //////////////////////////////////////////////////////////////


document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await fetch("../php_in_comune/getUser.php");
      const data = await response.json();

      connesso = data.success ? 1 : 0;
      console.log("Valore di connesso:", connesso);
  } catch (error) {
      console.error("Errore nel recupero dell'utente:", error);
  }
});



async function aggiornaPunteggio(canzone, punteggio) {
  try {
      const userId = await getUserId(); // Ottiene l'ID utente
      if (!userId) return;

      const response = await fetch('./php/updateScore.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
              'id': userId,
              'canzoneSelezionata': canzone,
              'score': punteggio
          })
      });

      const data = await response.json();
      console.log("Risposta server:", data.message || data.error);
  } catch (error) {
      console.error("Errore nella richiesta:", error);
  }
}



async function aggiornaMJC(id, incremento) {
  try {
      const response = await fetch('./php/updateField.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
              'id': id,
              'incremento': incremento
          })
      });

      // Leggi il testo della risposta prima di parsarlo come JSON
      const text = await response.text();
      console.log("Risposta del server:", text);

      // Ora prova a convertirlo in JSON
      const data = JSON.parse(text);
      
      if (data.success) {
          console.log(`Campo mjc aggiornato con successo di ${incremento}!`);
      } else {
          console.error("Errore:", data.error);
      }
  } catch (error) {
      console.error("Errore nella richiesta:", error); // riga 159
  }
}


// Funzione per recuperare l'ID dell'utente
async function getUserId() {
  try {
      const response = await fetch('./php/getUserId.php');
      const data = await response.json();
      if (data.success) {
          return data.id; // Restituisce l'ID dell'utente
      } else {
          console.error("Errore:", data.error);
          return null;
      }
  } catch (error) {
      console.error("Errore nella richiesta:", error);
      return null;
  }
}

// Chiamata combinata
async function aggiornaMJCConUtente(incremento) {
  const userId = await getUserId(); // Attendi che venga restituito l'ID utente
  aggiornaMJC(userId, incremento); // Chiama aggiornaMJC con l'ID utente
}



function drawStaticArrows() {
  let leftImg = document.getElementById("left");
  let leftSX = 1.5 * (canvas.width / 8);
  let downImg = document.getElementById("down");
  let downSX = 2.75 * (canvas.width / 8);
  let upImg = document.getElementById("up");
  let upSX = 4.0 * (canvas.width / 8);
  let rightImg = document.getElementById("right");
  let rightSX = 5.25 * (canvas.width / 8);

  let img;
  let sx;
  let sy = 40;
  let width = 75;
  let height = 75;
  ["left", "down", "up", "right"].forEach(dir => {
    switch (dir) {
      case "left":
        img = leftImg;
        sx = leftSX;
        break;
      case "down":
        img = downImg;
        sx = downSX;
        break;
      case "up":
        img = upImg;
        sx = upSX;
        break;
      case "right":
        img = rightImg;
        sx = rightSX;
    }
    ctx.drawImage(img, sx, sy, width, height);
  });
}

function popupDirections() {
  startModal.style.display = "none";
  directions.style.zIndex = 10;
  if (dirSty === "none") {
    directions.style.display = "flex";
  }
}

function gameStart(num) {
  // Nascondi la schermata iniziale
  modalOverlay.style.visibility = "hidden";
  playButton1.style.display = "none";
  playButton2.style.display = "none";
  playButton3.style.display = "none";
  playButton4.style.display = "none";
  playButton5.style.display = "none";
  //playButton6.style.display = "none";
  //playButton7.style.display = "none";
  directions.style.zIndex = 0;
  if (dirSty === "none") {
    directions.style.display = "none";
  }

  // Ripristina l'indice del countdown
  indice = 0;
  //salva la canzone selezionata
  canzoneSelezionata = num;

  startCountdown(() => {
    // Questo codice viene eseguito solo dopo il countdown
    if (num == 2) {
      mainSong.src = "../assets/audio/beat-it.mp3";
    } else if (num == 3) {
      mainSong.src = "../assets/audio/rock-with-you.mp3";
    } else if (num == 4) {
      mainSong.src = "../assets/audio/smooth-criminal.mp3";
    } else if (num == 5) {
      mainSong.src = "../assets/audio/thriller.mp3";
    }
    
    mainSong.play();
    arrowDraw();
    setInterval(draw, 1);
  });
}

function startCountdown(callback) {
  isCountdownActive = true;
  pauseIcon.style.pointerEvents = "none";
  restartIcon.style.pointerEvents = "none";
  countdownElement.classList.remove("hidden");

  if (indice === 0) {
    countdownSound.play();
  }

  if (indice < countdownNumbers.length) {
    const currentNumber = countdownNumbers[indice];

    // Cambia il testo del conto alla rovescia
    countdownElement.textContent = currentNumber;

    // Animazione
    countdownElement.classList.remove("pop-in");
    void countdownElement.offsetWidth; // Trigger reflow
    countdownElement.classList.add("pop-in");

    // Incrementa l'indice per il prossimo numero
    indice++;
    setTimeout(() => startCountdown(callback), 1000); // Attendi 1 secondo
  } else {
    // Fine del countdown, esegui la callback
    isCountdownActive = false;
    countdownElement.textContent = "";
    countdownElement.classList.add("hidden");
    indice = 0; // Reset dell'indice per il prossimo countdown
    pauseIcon.style.pointerEvents = "auto";
    restartIcon.style.pointerEvents = "auto";
    if (typeof callback === "function") {
      callback(); // Avvia il gioco
    }
  }
}


//sfondi randomici
function generateWallpaper() {
  random = 0;
  while(random == 0)
    random = Math.floor(Math.random() * 6);

  if (random == 1) {
    document.body.style.backgroundImage = 'url("https://images7.alphacoders.com/524/thumb-1920-524512.jpg")';
  } else if (random == 2) {
    document.body.style.backgroundImage = 'url("https://wallpapersok.com/images/hd/michael-joseph-jackson-black-poster-psbkr72mq48gdxsn.jpg")';
  } else if (random == 3) {
    document.body.style.backgroundImage = 'url("https://images2.alphacoders.com/152/152612.jpg")';
  } else if (random == 4) {
    document.body.style.backgroundImage = 'url("https://images5.alphacoders.com/568/568629.jpg")';
  } else if (random == 5) {
    document.body.style.backgroundImage = 'url("https://www.baltana.com/files/wallpapers-17/Michael-Jackson-HD-Desktop-Wallpaper-43832.jpg")';
  }
}

function arrowDraw() {
  if (ended || restart) {
    return;
  } else {
    if (!pause) {
      let nextArrow = arrowCreate();
      arrowArray.push(nextArrow);
      arrowArray[arrowArray.length - 1].drawArrow();
      arrowArray.forEach(arrow => (arrow.dy = -4));

      // Calcolo del punteggio massimo possibile
      if (arrowArray.length <= 10) {
        maxScore += 50;
      } else if (arrowArray.length > 10 && arrowArray.length <= 25) {
        maxScore += 75;
      } else if (arrowArray.length > 25 && arrowArray.length <= 50) {
        maxScore += 100;
      } else if (arrowArray.length > 50 && arrowArray.length <= 100) {
        maxScore += 150;
      } else if (arrowArray.length > 100) {
        maxScore += 200;
      }

      let time;
      if (arrowArray.length <= 20) {
        time = 600;
      } else if (arrowArray.length <= 40 && arrowArray.length > 20) {
        time = Math.floor(Math.random() * (600 - 400 + 1)) + 400;
      } else {
        time = Math.floor(Math.random() * (600 - 250 + 1)) + 250;
      }
      arrowDrawTimeout = setTimeout(arrowDraw, time);
    } else {
      for (let i = 0; i < arrowArray.length; i++) {
        arrowArray[i].dy = 0;
      }
      arrowDrawTimeout = setTimeout(arrowDraw, 100);
    }
  }
}

function arrowCreate() {
  let num = Math.floor(Math.random() * 4) + 1;
  switch (num) {
    case 1:
      return new ArrowSprite("left");
    case 2:
      return new ArrowSprite("down");
    case 3:
      return new ArrowSprite("up");
    case 4:
      return new ArrowSprite("right");
  }
}

function gamePause() {
  pause = !pause;
  if (pause) {
    mainSong.pause();
    pauseIcon.src = "../assets/images/game/play.png";
  } else {
    mainSong.play();
    pauseIcon.src = "../assets/images/game/pause.png";
  }
}

function gameRestart() {
  restarting();
  if (restart === true) {
    restart = false;
    playButton1.style.display = "flex";
    playButton2.style.display = "flex";
    playButton3.style.display = "flex";
    playButton4.style.display = "flex";
    playButton5.style.display = "flex";
    //playButton6.style.display = "flex";
    //playButton7.style.display = "flex";
    startModal.style.display = "flex";
    modalOverlay.style.visibility = "visible";
  }
}

function restarting() {
  generateWallpaper();
  clearTimeout(arrowDrawTimeout);
  restart = true;
  pause = false;
  clearNumbers();
  mainSong.pause();
  mainSong.currentTime = 0;
  arrowArray = arrowArray.map(arrow => {
    arrow.y = canvas.height;
    arrow.dy = 0;
  });
  arrowArray = [];

  // Ripristina l'elemento countdown
  if (!document.body.contains(countdownElement)) {
    const newCountdown = document.createElement("div");
    newCountdown.id = "countdown";
    newCountdown.classList.add("hidden");
    document.body.appendChild(newCountdown);
    countdownElement.classList.remove("hidden");
    countdownElement.textContent = ""; // Reset del testo
    indice = 0; // Reset dell'indice del countdown
  }
}


function clearNumbers() {
  score = 0;
  maxScore = 0;
  scoreDisplay.innerHTML = "Score: " + `${score}`;
  combo = 0;
  comboText.textContent = "combo";
  comboCount.innerHTML = "";
}

function songEnd() {
  ended = true;
  pauseIcon.style.pointerEvents = "none";
  restartIcon.style.pointerEvents = "none";
  if (ended === true) {
    if (score < (maxScore / 4)) {
      applause.src = "../assets/audio/Awh disappointed crowd sound effect.mp3";
      message.textContent = "Poteva andare meglio...   Grazie per aver giocato.";
    } else if (score == maxScore) {
      applause.src = "../assets/audio/perfect.mp3"
      if(connesso){
      message.textContent = "Sei stato PERFETTO!   Grazie per aver giocato.    Hai guadagnato 10 MJC.  (la valuta si aggiornerà il prima possibile!)";
      aggiornaMJCConUtente(10);
      }
    } else if(score >= (maxScore / 2)){
      if(connesso){
      message.textContent = "Grazie per aver giocato.   Hai guadagnato 3 MJC.   (la valuta si aggiornerà il prima possibile!)";
      aggiornaMJCConUtente(3);
      }
    } else{
      if(connesso){
      message.textContent = "Grazie per aver giocato.   Hai guadagnato 1 MJC.   (la valuta si aggiornerà il prima possibile!)";
      aggiornaMJCConUtente(1);
      }
    }
    applause.play();
  }
}

function gameEnd() {
  modalOverlay.style.visibility = "visible";
  endModal.style.display = "flex";
  aggiornaPunteggio(canzoneSelezionata, score);
}

function playAgain() {
  modalOverlay.style.visibility = "hidden";
  endModal.style.display = "none";
  clearNumbers();
  ended = false;
  pauseIcon.style.pointerEvents = "auto";
  restartIcon.style.pointerEvents = "auto";
  location.reload();
}

function toggleMute() {
  mainSong.muted = !mainSong.muted;
}

function handleKeyPress(e) {
  switch (e.keyCode) {
    case 37:
      leftPressed = !leftPressed;
      break;
    case 38:
      upPressed = !upPressed;
      break;
    case 39:
      rightPressed = !rightPressed;
      break;
    case 40:
      downPressed = !downPressed;
      break;
  }
}

function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  drawStaticArrows();

  for (let i = 0; i < arrowArray.length; i++) {
    if (combo > 0) {
      comboText.style.visibility = "visible";
      if (combo % 10 == 0) {
        if (combo == 20) {
          wowSound.play();
          comboText.textContent = "GREAT";
        } else if (combo == 50) {
          awesomeSound.play();
          comboText.textContent = "AWESOME";
        } else if (combo == 100) {
          amazingSound.play();
          comboText.textContent = "AMAZING";
        }
      }
    } else {
      comboText.style.visibility = "hidden";
    }

    if (leftPressed) {
      if (
        arrowArray[i].x === 84.375 &&
        arrowArray[i].y < 28 &&
        arrowArray[i].y > 1
      ) {
        if (arrowArray[i].combo === true) {
          combo += 1;
          arrowArray[i].combo = false;
        }
        comboCount.innerHTML = combo;

        if (arrowArray[i].points === true && combo <= 10) {
          score += 50;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 10 && combo <= 25) {
          score += 75;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 25 && combo <= 50) {
          score += 100;
          arrowArray[i].points = false;
        } else if (
          arrowArray[i].points === true &&
          combo > 50 &&
          combo <= 100
        ) {
          score += 150;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 100) {
          score += 200;
          arrowArray[i].points = false;
        }
        scoreDisplay.innerHTML = "Score: " + `${score}`;
        arrowArray[i].directionImage.src = "";
      }
    }

    if (downPressed) {
      if (
        arrowArray[i].x === 154.6875 &&
        arrowArray[i].y < 28 &&
        arrowArray[i].y > 1
      ) {
        if (arrowArray[i].combo === true) {
          combo += 1;
          arrowArray[i].combo = false;
        }
        comboCount.innerHTML = combo;

        if (arrowArray[i].points === true && combo <= 10) {
          score += 50;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 10 && combo <= 25) {
          score += 75;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 25 && combo <= 50) {
          score += 100;
          arrowArray[i].points = false;
        } else if (
          arrowArray[i].points === true &&
          combo > 50 &&
          combo <= 100
        ) {
          score += 150;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 100) {
          score += 200;
          arrowArray[i].points = false;
        }
        scoreDisplay.innerHTML = "Score: " + `${score}`;
        arrowArray[i].directionImage.src = "";
      }
    }

    if (upPressed) {
      if (
        arrowArray[i].x === 225 &&
        arrowArray[i].y < 28 &&
        arrowArray[i].y > 1
      ) {
        if (arrowArray[i].combo === true) {
          combo += 1;
          arrowArray[i].combo = false;
        }
        comboCount.innerHTML = combo;

        if (arrowArray[i].points === true && combo <= 10) {
          score += 50;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 10 && combo <= 25) {
          score += 75;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 25 && combo <= 50) {
          score += 100;
          arrowArray[i].points = false;
        } else if (
          arrowArray[i].points === true &&
          combo > 50 &&
          combo <= 100
        ) {
          score += 150;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 100) {
          score += 200;
          arrowArray[i].points = false;
        }
        scoreDisplay.innerHTML = "Score: " + `${score}`;
        arrowArray[i].directionImage.src = "";
      }
    }

    if (rightPressed) {
      if (
        arrowArray[i].x === 295.3125 &&
        arrowArray[i].y < 28 &&
        arrowArray[i].y > 1
      ) {
        if (arrowArray[i].combo === true) {
          combo += 1;
          arrowArray[i].combo = false;
        }
        comboCount.innerHTML = combo;

        if (arrowArray[i].points === true && combo <= 10) {
          score += 50;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 10 && combo <= 25) {
          score += 75;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 25 && combo <= 50) {
          score += 100;
          arrowArray[i].points = false;
        } else if (
          arrowArray[i].points === true &&
          combo > 50 &&
          combo <= 100
        ) {
          score += 150;
          arrowArray[i].points = false;
        } else if (arrowArray[i].points === true && combo > 100) {
          score += 200;
          arrowArray[i].points = false;
        }
        scoreDisplay.innerHTML = "Score: " + `${score}`;
        arrowArray[i].directionImage.src = "";
      }
    }

    if (arrowArray[i].y <= 1 && arrowArray[i].points !== false) {
      combo = 0;
      comboText.textContent = "combo";
      comboCount.innerHTML = "";
      arrowArray[i].points = false;
    }
  }
}
