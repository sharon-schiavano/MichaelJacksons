<?php
session_start();
header('Content-Type: application/json');
require_once "../../php_in_comune/config.php";

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato"]);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['canzoneSelezionata']) || !isset($_POST['score'])) {
    echo json_encode(["success" => false, "error" => "Dati mancanti"]);
    exit;
}

// Connessione al database
$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$db = pg_connect($connection_string);

if (!$db) {
    echo json_encode(["success" => false, "error" => "Errore di connessione al database"]);
    exit;
}

$id = $_POST['id'];
$canzoneSelezionata = $_POST['canzoneSelezionata'];
$score = $_POST['score'];

$campiCanzoni = [
    1 => "billie_jean",
    2 => "beat_it",
    3 => "rock_with_you",
    4 => "smooth_criminal",
    5 => "thriller"
];

if (!isset($campiCanzoni[$canzoneSelezionata])) {
    echo json_encode(["success" => false, "error" => "Canzone non valida"]);
    exit;
}

$campoDB = $campiCanzoni[$canzoneSelezionata];

// Recupero del punteggio attuale
$query = "SELECT $campoDB FROM utenti WHERE id = $1";
$result = pg_query_params($db, $query, [$id]);

if (!$result) {
    echo json_encode(["success" => false, "error" => "Errore nella query"]);
    exit;
}

$row = pg_fetch_assoc($result);

if (!$row) {
    echo json_encode(["success" => false, "error" => "Utente non trovato"]);
    exit;
}

$scoreAttuale = $row[$campoDB];

if ($score > $scoreAttuale) {
    $updateQuery = "UPDATE utenti SET $campoDB = $1 WHERE id = $2";
    $updateResult = pg_query_params($db, $updateQuery, [$score, $id]);

    if (!$updateResult) {
        echo json_encode(["success" => false, "error" => "Errore nell'aggiornamento del punteggio"]);
        exit;
    }

    echo json_encode(["success" => true, "message" => "Punteggio aggiornato con successo!"]);
} else {
    echo json_encode(["success" => true, "message" => "Nessun aggiornamento necessario"]);
}

pg_close($db);
?>
