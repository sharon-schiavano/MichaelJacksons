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

$pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_POST['id'];
$incremento = $_POST['incremento'];

$sql = "UPDATE utenti SET mjc = mjc + :incremento WHERE id = :id";
$stmt = $pdo->prepare($sql);

if (!$stmt->execute(['incremento' => $incremento, 'id' => $id])) {
    echo json_encode(["success" => false, "error" => "Errore nell'esecuzione della query"]);
    exit;
}

echo json_encode(["success" => true]);
?>
