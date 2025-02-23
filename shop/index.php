<?php
session_start();
require_once "../php_in_comune/config.php"; 

// Controllo connessione
if (!$db) {
    die("❌ Errore di connessione al database: " . pg_last_error());
}

// Query per recuperare i prodotti disponibili
$query = "SELECT * FROM prodotti ORDER BY tipo, id";
$result = pg_query($db, $query);

if (!$result) {
    die("Errore nella query: " . pg_last_error());
}

$prodotti = [];
while ($row = pg_fetch_assoc($result)) {
    $prodotti[] = $row;
}
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
            <?php foreach ($prodotti as $prodotto): ?>
                <?php if ($prodotto['tipo'] === 'prodotto'): ?>
                    <div class="product" data-id="<?= htmlspecialchars($prodotto['id']); ?>">
                        <img src="<?= htmlspecialchars($prodotto['immagine']); ?>" alt="<?= htmlspecialchars($prodotto['nome']); ?>" class="product-image">
                        <h3><?= htmlspecialchars($prodotto['nome']); ?></h3>
                        <p>Prezzo: <?= number_format($prodotto['prezzo'], 2); ?>€</p>
                        <button onclick="addToCart(<?= htmlspecialchars($prodotto['id']); ?>, '<?= addslashes($prodotto['nome']); ?>', <?= $prodotto['prezzo']; ?>)">Aggiungi al Carrello</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>

        <section id="songs">
            <h2 class="title">Canzoni Disponibili</h2>
            <?php foreach ($prodotti as $prodotto): ?>
                <?php if ($prodotto['tipo'] === 'canzone'): ?>
                    <div class="product" data-id="<?= htmlspecialchars($prodotto['id']); ?>">
                        <img src="<?= htmlspecialchars($prodotto['immagine']); ?>" alt="<?= htmlspecialchars($prodotto['nome']); ?>" class="product-image">
                        <h3><?= htmlspecialchars($prodotto['nome']); ?></h3>
                        <p>Prezzo: <?= number_format($prodotto['prezzo'], 2); ?>MJC</p>
                        <button onclick="purchaseSong('<?= addslashes($prodotto['nome']); ?>', <?= $prodotto['prezzo']; ?>)">Acquista</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    </main>

    <div id="cart-menu" class="hidden">
        <h2>Il tuo carrello</h2>
        <ul id="cart-items">
            <li>Il carrello è vuoto.</li>
        </ul>
        <p id="total">Totale: 0 €</p>

        <!-- Form per il pagamento con Stripe -->
        <form id="payment-form">
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
            <button id="submit-button">Paga Ora</button>
        </form>

        <!-- 🔹 Pulsante CHIUDI -->
        <button onclick="toggleCart()" class="cart-close-btn">Chiudi</button>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="checkout.js"></script>

    <?php include '../footer/footer.html'; ?>
</body>
</html>
