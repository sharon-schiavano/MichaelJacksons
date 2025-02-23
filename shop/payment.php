<?php
require '../php_in_comune/config.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Metodo non valido']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
    echo json_encode(['error' => 'Importo non valido']);
    exit();
}

try {
    // Creazione del PaymentIntent con Stripe
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $data['amount'], // Convertito in centesimi
        'currency' => 'eur',
        'payment_method_types' => ['card'],
        'description' => 'Acquisto dallo Shop MJStillAlive'
    ]);

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
