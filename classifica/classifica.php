<?php
include '../php_in_comune/config.php';

// Recupera il criterio di ordinamento dal parametro GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'mjc';

// Convalida il criterio di ordinamento
$allowedSortFields = ['mjc', 'billie_jean', 'thriller', 'rock_with_you', 'beat_it', 'smooth_criminal'];
if (!in_array($sort, $allowedSortFields)) {
    $sort = 'mjc';
}

// Modifica la query per ordinare in base al criterio scelto
$query = "SELECT id, username, $sort, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller FROM utenti ORDER BY $sort DESC LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
