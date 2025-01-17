const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const replayButton = document.getElementById('replayButton');

// dimensione di michael
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// variabili
const basket = {
    x: canvas.width / 2 - 50,
    y: canvas.height - 100,
    width: 100,
    height: 50,
    color: 'brown',
};

const fruits = [];
const fruitRadius = 20;
let score = 0;
let lives = 3;
const maxLives = 3;
let gameOver = false;
let positiveFruitCount = 0;
let isSpecialState = false;
let specialStateTimer;
let cont = 1;

let audio = new Audio("protezione.mp3"); // Placeholder for background music

// Variabili per il reset
let timerResetInterval = 30000; // 20 secondi

// Funzione per resettare le variabili della generazione frutti
function resetFruitGeneration() {
    positiveFruitCount = 0; // Reset del conteggio dei frutti positivi
    if(cont == 50) cont = 1; // Reset del contatore 
    console.log('Fruit generation variables have been reset.');
}

// Timer per resettare le variabili ogni 20 secondi
setInterval(resetFruitGeneration, timerResetInterval);

// Funzione per creare i frutti (rimane invariata tranne per l'uso delle variabili sopra)
function createFruit() {
    if (isSpecialState) {
        fruits.push({
            x: Math.random() * (canvas.width - fruitRadius * 2) + fruitRadius,
            y: -fruitRadius,
            speed: 8 + Math.random() * 4,
            type: 'special',
        });
        return;
    }

    const x = Math.random() * (canvas.width - fruitRadius * 2) + fruitRadius;
    let type = 'positive';

    if (cont > 0 && cont % 30 === 0) {
        type = 'star';
    } else if (cont > 0 && cont % 50 === 0) {
        type = 'life';
    } else if (cont > 0 && cont % 10 === 0) {
        type = 'albano10';
    } else if (Math.random() < 0.3) {
        type = 'albano1';
    }

    const baseSpeed = 5;
    const maxSpeed = 15;
    const timeFactor = 0.1;

    if (type === 'star') {
        fruits.push({ x, y: -fruitRadius, speed: 17.5, type });
    } else {
        fruits.push({ x, y: -fruitRadius, speed: baseSpeed + (Math.log(cont)), type }); //ho usato la logaritmica perché cresce lentamente. TIZIANA SII FIERA DI ME
    }
    cont++;
}


// sarebbe michael
function drawBasket() {
    ctx.fillStyle = basket.color;
    ctx.fillRect(basket.x, basket.y, basket.width, basket.height);
}

// i frutti
function drawFruits() {
    fruits.forEach(fruit => {
        ctx.beginPath();
        ctx.arc(fruit.x, fruit.y, fruitRadius, 0, Math.PI * 2);

        switch (fruit.type) {
            case 'positive': ctx.fillStyle = 'red'; break;
            case 'albano1': ctx.fillStyle = 'blue'; break;
            case 'albano10': ctx.fillStyle = 'purple'; break;
            case 'life': ctx.fillStyle = 'green'; break;
            case 'star': ctx.fillStyle = 'yellow'; break;
            case 'special': ctx.fillStyle = 'orange'; break;
        }

        ctx.fill();
        ctx.closePath();
    });
}

// i fruitti si muovono
function moveFruits() {
    fruits.forEach(fruit => {
        fruit.y += fruit.speed;

        if (
            fruit.y + fruitRadius >= basket.y &&
            fruit.y - fruitRadius <= basket.y + basket.height &&
            fruit.x > basket.x &&
            fruit.x < basket.x + basket.width
        ) {
            switch (fruit.type) {
                case 'positive': score++; positiveFruitCount++; break;
                case 'albano1': score = Math.max(0, score - 1); lives--; break;
                case 'albano10': score = Math.max(0, score - 10); lives--; break;
                case 'life': if (lives < maxLives) lives++; break;
                case 'star': enterSpecialState(); break;
                case 'special': score += 10; break;
            }
            fruits.splice(fruits.indexOf(fruit), 1);
            if (lives <= 0) gameOver = true;
        }

        if (fruit.y - fruitRadius > canvas.height) {
            if (fruit.type === 'positive') lives--;
            fruits.splice(fruits.indexOf(fruit), 1);
            if (lives <= 0) gameOver = true;
        }
    });
}

// michael con la stella
function enterSpecialState() {
    isSpecialState = true;
    fruits.length = 0;
    audio.loop = true;
    audio.play();

    specialStateTimer = setTimeout(() => {
        isSpecialState = false;
        audio.pause();
        audio.currentTime = 0;
    }, 10000);
}

// punteggio e vite
function drawScoreAndLives() {
    ctx.font = '20px Arial';
    ctx.fillStyle = 'black';
    ctx.fillText(`Score: ${score}`, 20, 30);
    ctx.fillText(`Lives: ${lives}`, 20, 60);
}

// game over
function drawGameOver() {
    ctx.font = '50px Arial';
    ctx.fillStyle = 'black';
    ctx.textAlign = 'center';
    ctx.fillText('Game Over', canvas.width / 2, canvas.height / 2);
    replayButton.style.display = 'block';
}

// Game loop
function gameLoop() {
    if (gameOver) {
        drawGameOver();
        return;
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    drawBasket();
    drawFruits();
    drawScoreAndLives();

    moveFruits();

    requestAnimationFrame(gameLoop);
}

// Handle basket movement with mouse
canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    basket.x = mouseX - basket.width / 2;

    if (basket.x < 0) basket.x = 0;
    if (basket.x + basket.width > canvas.width) basket.x = canvas.width - basket.width;
});

// Replay button click event
replayButton.addEventListener('click', () => {
    window.location.reload();
});

// Spawn fruits at intervals
setInterval(createFruit, 800);

// Start the game loop
gameLoop();
