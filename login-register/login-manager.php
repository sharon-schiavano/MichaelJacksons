<?php

    require_once "config.php";

    function controlloInput($input) {       //vado a vedere se l'input ricevuto è uno username o una e-mail.

        return strpos($input,"@");       //se è >=0 allora c'è una @ e quindi va considerata come un possibile indirizzo e-mail; altrimenti va considerato come uno username
    }


    $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());

	session_start();

	$input = pg_escape_string($db,$_POST["usernamelogin"]);

	$_SESSION['oldinput'] = $input;

	$password = $_POST["passwordlogin"];

	if ( controlloInput($input) > -1 )		//ho inserito in questo campo una e-mail
		$sql = "SELECT * FROM utenti WHERE email = $1 ";

	else 	
		$sql = "SELECT * FROM utenti WHERE username = $1 ";

	$ret = pg_prepare($db, "selezionaUtente", $sql);

	if (!$ret) {

		echo "Errore durante la preparazione della query: " . pg_last_error();
		exit;

	}



	$ret = pg_execute($db,"selezionaUtente",array($input));

	if (pg_num_rows($ret) == 0) {

		if (controlloInput($input) > -1) {
		//	echo "Errore: l'email $input non è associata ad alcun account!";
			$_SESSION['bademail'] = true;
		}
		else  {
		//	echo "Errore: username $input non esiste!";
			$_SESSION['badusername'] = true;

			}
		
		header("location: index.php");
		exit;
	}
	
	
	$row = pg_fetch_assoc($ret);

	if ( password_verify ($password, $row['password'] ) ) {

		$_SESSION['username'] = $row['username'];
		$_SESSION['logged'] = true;

		header("Location: ../homepage/homepage.html");

	} else {
		$_SESSION['badpassword'] = true;
		header("Location: index.php");
	}


	 
	

?>
