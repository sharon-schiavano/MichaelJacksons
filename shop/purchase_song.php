<?php
session_start();
require_once "../php_in_comune/config.php"; // Assicurati che il percorso sia corretto

header('Content-Type: application/json');

//  Controlla se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "❌ Devi essere loggato per acquistare."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents("php://input"), true);

//  Controlla che i dati siano corretti
if (!isset($input['song']) || !isset($input['price'])) {
    echo json_encode(["success" => false, "message" => "❌ Dati mancanti per l'acquisto."]);
    exit;
}

$song_column = $input['song'];
$price = intval($input['price']);

//  Controlla che la canzone sia valida
$allowed_songs = ["billie_jean", "beat_it", "smooth_criminal", "thriller"];
if (!in_array($song_column, $allowed_songs)) {
    echo json_encode(["success" => false, "message" => "❌ Canzone non valida."]);
    exit;
}

//  Controlla il saldo dell'utente e se la canzone è già acquistata
$query = "SELECT mjc, unlocked_$song_column FROM utenti WHERE id = $1";
$result = pg_query_params($conn, $query, [$user_id]);

if (!$result) {
    echo json_encode(["success" => false, "message" => "❌ Errore nel recupero dati utente: " . pg_last_error($conn)]);
    exit;
}

$user = pg_fetch_assoc($result);

//  Se la canzone è già acquistata
if ($user["unlocked_$song_column"]) {
    echo json_encode(["success" => false, "message" => "⚠️ Hai già acquistato questa canzone."]);
    exit;
}

//  Se l'utente non ha abbastanza MJC
if ($user['mjc'] < $price) {
    echo json_encode(["success" => false, "message" => "❌ Fondi insufficienti. Hai solo " . $user['mjc'] . " MJC."]);
    exit;
}

// Effettua l'acquisto: aggiorna saldo e sblocca la canzone
$update_query = "UPDATE utenti SET mjc = mjc - $1, unlocked_$song_column = TRUE WHERE id = $2";
$update_result = pg_query_params($conn, $update_query, [$price, $user_id]);

if ($update_result) {
    // Recupera il nuovo saldo
    $new_balance_query = "SELECT mjc FROM utenti WHERE id = $1";
    $new_balance_result = pg_query_params($conn, $new_balance_query, [$user_id]);
    $new_balance = pg_fetch_assoc($new_balance_result)['mjc'];

    $_SESSION['mjc'] = $new_balance; // Aggiorna il saldo MJC nella sessione

    echo json_encode(["success" => true, "message" => "✅ Acquisto completato!", "new_balance" => $new_balance]);
} else {
    echo json_encode(["success" => false, "message" => "❌ Errore durante l'acquisto: " . pg_last_error($conn)]);
}
?>



