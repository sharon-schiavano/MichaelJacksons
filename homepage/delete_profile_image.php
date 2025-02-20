<?php
session_start();
require_once "../php_in_comune/config.php";

$response = ['success' => false];

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    // Prima recupera il percorso dell'immagine attuale
    $query = "SELECT immagine_profilo FROM utenti WHERE id = $1";
    $result = pg_prepare($db, "get_image", $query);
    $result = pg_execute($db, "get_image", array($user_id));
    
    if ($row = pg_fetch_assoc($result)) {
        $current_image = $row['immagine_profilo'];
        
        // Se esiste un'immagine e non è quella di default, eliminala dal filesystem
        if (!empty($current_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $current_image) && 
            strpos($current_image, 'default.jpg') === false) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $current_image);
        }
    }
    
    // Aggiorna il database con il percorso dell'immagine di default
    $default_image = '../uploads/default.jpg';
    $query = "UPDATE utenti SET immagine_profilo = $1 WHERE id = $2";
    $result = pg_prepare($db, "update_image", $query);
    $result = pg_execute($db, "update_image", array($default_image, $user_id));
    
    if ($result) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>