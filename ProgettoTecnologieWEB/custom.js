var game = document.querySelector(".game");
var MJ = document.querySelector(".MJ");
var fruits = document.querySelector(".fruits");
var MJLeft = parseInt(window.getComputedStyle(MJ).getPropertyValue("left"));
var MJBottom = parseInt(window.getComputedStyle(MJ).getPropertyValue("bottom"));
var score = 0;

document.addEventListener("mousemove", function (e) {
    MJLeft = e.clientX - game.offsetLeft - MJ.offsetWidth / 2;
    if (MJLeft < 0) MJLeft = 0; 
    if (MJLeft > 620) MJLeft = 620; 
    MJ.style.left = MJLeft + "px";
});

function generateFruits() {
    var fruitBottom = window.innerHeight; 
    var fruitLeft = Math.floor(Math.random() * 620);
    var fruit = document.createElement("div");
    fruit.setAttribute("class", "fruit");
    fruit.style.left = fruitLeft + "px";
    fruit.style.bottom = fruitBottom + "px"; 
    fruits.appendChild(fruit);

    function fallDownFruit() {
        // Verifica se MJ raccoglie il frutto correttamente
        if (
            fruitBottom < MJBottom + 50 &&
            fruitBottom > MJBottom &&
            fruitLeft > MJLeft - 30 &&
            fruitLeft < MJLeft + 80
        ) {
            fruits.removeChild(fruit);
            clearInterval(fallInterval);
            score++;
        }

        if (fruitBottom < MJBottom) {
            alert("Game Over! Your score is: " + score);
            clearInterval(fallInterval);
            clearTimeout(fruitTimeout);
            location.reload();
        }

        fruitBottom -= 10;
        fruit.style.bottom = fruitBottom + "px";
        fruit.style.left = fruitLeft + "px";
    }

    var fallInterval = setInterval(fallDownFruit, 20);
    var fruitTimeout = setTimeout(generateFruits, 2000);
}

generateFruits();
