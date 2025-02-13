<?php
session_start();

// Verifica se l'utente è loggato
if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    // Restituisce l'ID dell'utente come risposta JSON
    echo json_encode(["success" => true, "id" => $_SESSION['id']]);
} else {
    // Restituisce errore se l'utente non è loggato
    echo json_encode(["success" => false, "error" => "Utente non loggato"]);
}
?>
