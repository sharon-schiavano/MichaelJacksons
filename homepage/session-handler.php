<?php
session_start();
header('Content-Type: application/json');

$response = [];

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    $response['logged_in'] = true;
    $response['username'] = $_SESSION['username'];
} else {
    $response['logged_in'] = false;
}

echo json_encode($response);
?>
