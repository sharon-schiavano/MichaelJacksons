<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Riuscito</title>
    <link rel="stylesheet" href="shop.css">
    <link rel="stylesheet" href="success.css">
</head>
<body>

    <main>
        <section class="success-message">
            <h1>Pagamento Completato!</h1>
            <p>Grazie per il tuo acquisto.</p>
            <a href="index.php">Torna allo shop</a>
        </section>
    </main>

    <footer>
        <?php include '../footer/footer.html'; ?>
    </footer>
    
</body>
</html>

