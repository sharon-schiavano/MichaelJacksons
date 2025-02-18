<?php
include '../php_in_comune/config.php';

if (!$db) {
    echo json_encode(["success" => false, "error" => "Errore di connessione al database"]);
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = "SELECT username, email, mjc, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller 
              FROM utenti 
              WHERE id = $1";

    $result = pg_query_params($db, $query, [$id]);

    if (!$result) {
        echo json_encode(["success" => false, "error" => "Errore nell'esecuzione della query"]);
        exit;
    }

    $user = pg_fetch_assoc($result);
    pg_close($db);
    
    echo json_encode($user);
}
?>
