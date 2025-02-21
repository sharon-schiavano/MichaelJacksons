<?php
session_start();

if($_SERVER["REQUEST_METHOD"] != "POST"){
    exit("POST request method required");
}

if(empty($_FILES)){
    exit("$_FILES is empty");
}

if($_FILES["image"]["error"] !== UPLOAD_ERR_OK){
    switch($_FILES["image"]["error"]){
        case UPLOAD_ERR_PARTIAL:
            exit("File only partially uploaded");
        case UPLOAD_ERR_NO_FILE:
            exit("No file was uploaded");
        case UPLOAD_ERR_EXTENSION:
            exit("File upload stopped by a PHP extension");
        default:
            exit("Unknown upload error");
    }
}

$mime_types = ["image/gif", "image/png", "image/jpeg","image/jpg"];
if(!in_array($_FILES["image"]["type"], $mime_types)){
    exit("Invalid file format");
}

$filename = $_FILES["image"]["name"];
$destination = __DIR__ . "/../uploads/profile_images/" . $filename;
if(!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)){
    exit("Can't move uploaded file");
}

/* --- Comandi necessari per aggiornare il database --- */

// Assicurati che l'utente sia loggato
if(!isset($_SESSION['id'])){
    exit("User not logged in");
}
$user_id = $_SESSION['id'];

// Il percorso da salvare nel database (relativo alla root del progetto)
$image_path = "/gruppo07/uploads/profile_images/" . $filename;

// Include la configurazione del database (assicurati che in config.php sia definita la connessione, ad es. $db)
require_once "../php_in_comune/config.php";

// Prepara ed esegui la query per aggiornare l'immagine di profilo
$sql = "UPDATE utenti SET immagine_profilo = $1 WHERE id = $2";
$ret = pg_prepare($db, "update_profile_image", $sql);
if(!$ret){
    exit("DB prepare error: " . pg_last_error($db));
}
$ret = pg_execute($db, "update_profile_image", array($image_path, $user_id));
if(!$ret){
    exit("DB update error: " . pg_last_error($db));
}

echo "Profile image updated successfully!";

?>



