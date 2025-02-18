<?php
include '../php_in_comune/config.php';


if (!$db) {
    echo json_encode(["success" => false, "error" => "Errore di connessione al database"]);
    exit;
}

// Recupera il criterio di ordinamento dal parametro GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'mjc';

// Convalida il criterio di ordinamento
$allowedSortFields = ['mjc', 'billie_jean', 'thriller', 'rock_with_you', 'beat_it', 'smooth_criminal'];
if (!in_array($sort, $allowedSortFields)) {
    $sort = 'mjc';
}

// Query per ottenere la classifica
$query = "SELECT id, username, $sort, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller 
          FROM utenti 
          ORDER BY $sort DESC 
          LIMIT 10";

$result = pg_query($db, $query);

if (!$result) {
    echo json_encode(["success" => false, "error" => "Errore nell'esecuzione della query"]);
    exit;
}

$users = pg_fetch_all($result);
pg_close($db);

echo json_encode($users);
?>
