<?php
session_start(); // Inizia la sessione qui

require_once "config.php";

function controlloInput($input) {
    return strpos($input, "@"); // Controlla se l'input è un'email
}

$db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());

$input = pg_escape_string($db, $_POST["usernamelogin"]);
$password = $_POST["passwordlogin"];

if (controlloInput($input) > -1) {
    $sql = "SELECT * FROM utenti WHERE email = $1";
} else {
    $sql = "SELECT * FROM utenti WHERE username = $1";
}

$ret = pg_prepare($db, "selezionaUtente", $sql);

if (!$ret) {
    echo "Errore nella preparazione della query! " . pg_last_error();
    exit;
}

$ret = pg_execute($db, "selezionaUtente", array($input));

if (pg_num_rows($ret) == 0) {
    if (controlloInput($input) > -1) {
        $_SESSION['bademail'] = $input;
    } else {
        $_SESSION['badusername'] = $input;
    }
    header("location: index.php"); // Torna alla pagina di login
    exit;
}

$row = pg_fetch_assoc($ret);

if (password_verify($password, $row['password'])) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['logged'] = true;
    
    header("Location: ../homepage/UtenteRegistrato.html"); // Reindirizza alla homepage per utenti registrati
} else {
    $_SESSION['badpassword'] = $password;
    header("Location: index.php");
}
?>
