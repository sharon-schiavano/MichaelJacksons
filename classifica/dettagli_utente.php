<?php
include '../php_in_comune/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT username, email, mjc, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller FROM utenti WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}
