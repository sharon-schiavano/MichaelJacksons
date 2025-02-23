<?php
session_start();
require_once "../php_in_comune/config.php"; 

header('Content-Type: application/json');

// Controllo se l'utente è loggato
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    echo json_encode(["success" => false, "message" => "❌ Devi essere loggato per acquistare."]);
    exit;
}

$user_id = $_SESSION['id'];
$input = json_decode(file_get_contents("php://input"), true);

// Controllo che i dati siano corretti
if (!isset($input['song']) || !isset($input['price'])) {
    echo json_encode(["success" => false, "message" => "❌ Dati mancanti per l'acquisto."]);
    exit;
}

$song_column = strtolower(str_replace(" ", "", $input['song'])); // Rimuove spazi dal nome
$price = intval($input['price']);

// Mappa delle canzoni con i nomi delle colonne effettivi
$song_mapping = [
    "billie_jean" => "unlocked_billiejean",
    "beat_it" => "unlocked_beatit",
    "smooth_criminal" => "unlocked_smoothcriminal",
    "thriller" => "unlocked_thriller"
];

// Controllo se la canzone è valida
if (!isset($song_mapping[$song_column])) {
    echo json_encode(["success" => false, "message" => "❌ Canzone non valida."]);
    exit;
}

$column_name = $song_mapping[$song_column];

// Controllo il saldo dell'utente e se la canzone è già acquistata
$query = "SELECT valuta, $column_name FROM utenti WHERE id = $1";
$result = pg_query_params($db, $query, [$user_id]);

if (!$result) {
    echo json_encode(["success" => false, "message" => "❌ Errore nel recupero dati utente: " . pg_last_error($db)]);
    exit;
}

$user = pg_fetch_assoc($result);
$user_balance = intval($user['valuta']);

// Se la canzone è già acquistata
if ($user[$column_name] === 't') {
    echo json_encode(["success" => false, "message" => "⚠ Hai già acquistato questa canzone."]);
    exit;
}

// Se l'utente non ha abbastanza MJC
if ($user_balance < $price) {
    echo json_encode(["success" => false, "message" => "❌ Fondi insufficienti. Hai solo " . $user_balance . " MJC."]);
    exit;
}

// Effettua l'acquisto: aggiorna saldo e sblocca la canzone
$update_query = "UPDATE utenti SET valuta = valuta - $1, $column_name = TRUE WHERE id = $2";
$update_result = pg_query_params($db, $update_query, [$price, $user_id]);

if ($update_result) {
    // Recupero il nuovo saldo e lo stato della canzone acquistata
    $new_balance_query = "SELECT valuta, unlocked_billiejean, unlocked_beatit, unlocked_smoothcriminal, unlocked_thriller FROM utenti WHERE id = $1";
    $new_balance_result = pg_query_params($db, $new_balance_query, [$user_id]);
    $new_data = pg_fetch_assoc($new_balance_result);

    if ($new_data) {
        // si aggiorna immediatamente la sessione
        $_SESSION['valuta'] = $new_data['valuta'];
        $_SESSION['unlocked_billiejean'] = $new_data['unlocked_billiejean'];
        $_SESSION['unlocked_beatit'] = $new_data['unlocked_beatit'];
        $_SESSION['unlocked_smoothcriminal'] = $new_data['unlocked_smoothcriminal'];
        $_SESSION['unlocked_thriller'] = $new_data['unlocked_thriller'];

        echo json_encode([
            "success" => true,
            "message" => "✅ Acquisto completato!",
            "new_balance" => $_SESSION['valuta'],
            "unlocked_billiejean" => $_SESSION['unlocked_billiejean'],
            "unlocked_beatit" => $_SESSION['unlocked_beatit'],
            "unlocked_smoothcriminal" => $_SESSION['unlocked_smoothcriminal'],
            "unlocked_thriller" => $_SESSION['unlocked_thriller']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "❌ Errore nel recupero del nuovo stato dell'utente."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "❌ Errore durante l'acquisto: " . pg_last_error($db)]);
}
?>
