<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | MJStillAlive</title>
    <link rel="stylesheet" href="shop.css">
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <script src="cart.js" defer></script>
</head>
<body>
    <header>
        <h1>MJStillAlive Shop</h1>
        <div id="cart-icon">
            🛒 Carrello (<span id="cart-count">0</span>)
        </div>
    </header>

    <?php include '../sidebar/sidebar.html'; ?>

    <main>
        <section id="products">
            <h2 class="title">Prodotti Disponibili</h2>
            <div class="product" data-id="1">
                <img src="../assets/images/shop/thriller.jpg" alt="Thriller Album" class="product-image">
                <h3>Thriller (Album)</h3>
                <p>Prezzo: 15€</p>
                <button onclick="addToCart(1, 'Thriller (Album)', 15)">Aggiungi al Carrello</button>
            </div>
            <div class="product" data-id="2">
                <img src="../assets/images/shop/bad.jpg" alt="Bad Album" class="product-image">
                <h3>Bad (Album)</h3>
                <p>Prezzo: 12€</p>
                <button onclick="addToCart(2, 'Bad (Album)', 12)">Aggiungi al Carrello</button>
            </div>
            <div class="product" data-id="3">
                <img src="../assets/images/shop/funkomj1.png" alt="FunkoPop! MJ" class="product-image">
                <h3>FunkoPop! MJ</h3>
                <p>Prezzo: 20€</p>
                <button onclick="addToCart(3, 'FunkoPop! MJ', 20)">Aggiungi al Carrello</button>
            </div>
            <div class="product" data-id="4">
                <img src="../assets/images/shop/mjtee.png" alt="Maglietta MJ" class="product-image">
                <h3>Maglietta MJ</h3>
                <p>Prezzo: 20€</p>
                <button onclick="addToCart(4, 'Maglietta MJ', 20)">Aggiungi al Carrello</button>
            </div>
            <div class="product" data-id="5">
                <img src="../assets/images/shop/posterjackson.jpg" alt="Maglietta MJ" class="product-image">
                <h3>Poster MJ</h3>
                <p>Prezzo: 10€</p>
                <button onclick="addToCart(5, 'Poster MJ', 10)">Aggiungi al Carrello</button>
            </div>
            <div class="product" data-id="6">
                <img src="../assets/images/shop/jacksonwii.jpg" alt="Maglietta MJ" class="product-image">
                <h3>MJ "The Experience" Wii</h3>
                <p>Prezzo: 30€</p>
                <button onclick="addToCart(6, 'MJ The Experience Wii', 30)">Aggiungi al Carrello</button>
            </div>
        </section>

        <section id="songs">
            <h2 class="title">Canzoni Disponibili</h2>
            <div class="product" data-id="7">
                <img src="../assets/images/shop/billiejean.jpg" alt="Billie Jean" class="product-image">
                <h3>Billie Jean</h3>
                <p>Prezzo: 10MJC</p>
                <button onclick="purchaseSong('billie_jean', 10)">Acquista</button> <!-- 🔥 Cambiato -->
            </div>
            <div class="product" data-id="8">
                <img src="../assets/images/shop/beatit.png" alt="Beat It" class="product-image">
                <h3>Beat It</h3>
                <p>Prezzo: 6MJC</p>
                <button onclick="purchaseSong('beat_it', 6)">Acquista</button> <!-- 🔥 Cambiato -->
            </div>
            <div class="product" data-id="9">
                <img src="../assets/images/shop/smoothcriminal.jpg" alt="Smooth Criminal" class="product-image">
                <h3>Smooth Criminal</h3>
                <p>Prezzo: 5MJC</p>
                <button onclick="purchaseSong('smooth_criminal', 5)">Acquista</button> <!-- 🔥 Cambiato -->
            </div>
            <div class="product" data-id="10">
                <img src="../assets/images/shop/thrillersong.jpg" alt="Thriller" class="product-image">
                <h3>Thriller (Song)</h3>
                <p>Prezzo: 8MJC</p>
                <button onclick="purchaseSong('thriller', 8)">Acquista</button> <!-- 🔥 Cambiato -->
            </div>
        </section>
    </main>

    <div id="cart-menu" class="hidden">
        <h2>Il tuo carrello</h2>
        <ul id="cart-items">
            <li>Il carrello è vuoto.</li>
        </ul>
        <p id="total">Totale: 0 €</p>
        <button onclick="checkout()">Procedi al pagamento</button>
        <button onclick="toggleCart()">Chiudi</button>
    </div>

    <?php include '../footer/footer.html'; ?>
</body>
</html>

