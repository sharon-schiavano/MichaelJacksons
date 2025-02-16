<?php
session_start();
header('Content-Type: application/json');

require_once "../../php_in_comune/config.php";

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato"]);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['incremento'])) {
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
$incremento = $_POST['incremento'];

// Query per aggiornare il campo mjc
$sql = "UPDATE utenti SET mjc = mjc + $1 WHERE id = $2";
$result = pg_query_params($db, $sql, [$incremento, $id]);

if (!$result) {
    echo json_encode(["success" => false, "error" => "Errore nell'esecuzione della query"]);
    exit;
}

// Query per aggiornare la valuta in sessione
$sql = "SELECT valuta FROM utenti WHERE id = $1";
$result = pg_query_params($db, $sql, [$id]);

if ($row = pg_fetch_assoc($result)) {
    $_SESSION['valuta'] = $row['valuta'];
}

pg_close($db);

echo json_encode(["success" => true]);
?>
