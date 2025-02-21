<?php
session_start();
require_once "../php_in_comune/config.php"; 

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "❌ Devi essere loggato per acquistare."]);
    exit;
}

$user_id = $_SESSION['id'];
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['song'])) {
    echo json_encode(["success" => false, "message" => "❌ Dati mancanti per l'acquisto."]);
    exit;
}

$song_column = $input['song'];
$allowed_songs = ["billie_jean", "beat_it", "smooth_criminal", "thriller"];
$unlocked_column = "unlocked_" . $song_column;

if (!in_array($song_column, $allowed_songs)) {
    echo json_encode(["success" => false, "message" => "❌ Canzone non valida."]);
    exit;
}

$query = "SELECT valuta, $unlocked_column FROM utenti WHERE id = $1";
$result = pg_query_params($conn, $query, [$user_id]);

if (!$result) {
    echo json_encode(["success" => false, "message" => "❌ Errore nel recupero dati utente."]);
    exit;
}

$user = pg_fetch_assoc($result);
$prezzi_canzoni = ["billie_jean" => 10, "beat_it" => 6, "smooth_criminal" => 5, "thriller" => 8];
$prezzo = $prezzi_canzoni[$song_column];

if ($user[$unlocked_column]) {
    echo json_encode(["success" => false, "message" => "⚠️ Hai già acquistato questa canzone."]);
    exit;
}

if ($user['valuta'] < $prezzo) {
    echo json_encode(["success" => false, "message" => "❌ Fondi insufficienti. Hai solo " . $user['valuta'] . " MJC."]);
    exit;
}

$update_query = "UPDATE utenti SET valuta = valuta - $1, $unlocked_column = TRUE WHERE id = $2";
$update_result = pg_query_params($conn, $update_query, [$prezzo, $user_id]);

if ($update_result) {
    $_SESSION['valuta'] -= $prezzo;
    $_SESSION[$unlocked_column] = true;

    echo json_encode(["success" => true, "message" => "✅ Acquisto completato!", "new_balance" => $_SESSION['valuta']]);
} else {
    echo json_encode(["success" => false, "message" => "❌ Errore durante l'acquisto."]);
}
?>
