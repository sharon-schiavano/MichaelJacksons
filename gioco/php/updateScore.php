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

$pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

$query = "SELECT $campoDB FROM utenti WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "error" => "Utente non trovato"]);
    exit;
}

$scoreAttuale = $row[$campoDB];

if ($score > $scoreAttuale) {
    $updateQuery = "UPDATE utenti SET $campoDB = :score WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['score' => $score, 'id' => $id]);

    echo json_encode(["success" => true, "message" => "Punteggio aggiornato con successo!"]);
} else {
    echo json_encode(["success" => true, "message" => "Nessun aggiornamento necessario"]);
}
?>
