<?php
session_start();
require_once "../php_in_comune/config.php";

$response = ['success' => false];

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    // Prima recupero il percorso dell'immagine attuale
    $current_image = $_SESSION['immagine_profilo'];

    // Se esiste un'immagine e non è quella di default, la elimino dal filesystem
        if (!empty($current_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . $current_image) && 
            strpos($current_image, 'default.jpg') === false) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $current_image);
        }
    
    
    // Aggiorno il database con il percorso dell'immagine di default
    $default_image = '../uploads/profile_images/default.jpg';
    $query = "UPDATE utenti SET immagine_profilo = $1 WHERE id = $2";
    $result = pg_prepare($db, "update_image", $query);
    $result = pg_execute($db, "update_image", array($default_image, $user_id));
    
    if ($result) {
        $_SESSION['immagine_profilo'] = $default_image;
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>