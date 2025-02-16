<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    echo json_encode([
        "success" => true,
        "id" => $_SESSION['id'],
        "username" => $_SESSION['username'],
        "valuta" => $_SESSION['valuta'],
        "unlocked_billiejean" => $_SESSION['unlocked_billiejean'],
        "unlocked_beatit" => $_SESSION['unlocked_beatit'],
        "unlocked_smoothcriminal" => $_SESSION['unlocked_smoothcriminal'],
        "unlocked_thriller" => $_SESSION['unlocked_thriller'],
        "billie_jean" => $_SESSION['billie_jean'],
        "beat_it" => $_SESSION['beat_it'],
        "rock_with_you" => $_SESSION['rock_with_you'],
        "smooth_criminal" => $_SESSION['smooth_criminal'],
        "immagine_profilo" => $_SESSION['immagine_profilo'],
        "thriller" => $_SESSION['thriller'],
        "mjc" => $_SESSION['mjc']
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
