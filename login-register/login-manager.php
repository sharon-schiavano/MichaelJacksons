<?php

	require_once "../php_in_comune/config.php";

    function controlloInput($input) {       //vado a vedere se l'input ricevuto è uno username o una e-mail.

        return strpos($input,"@");       //se è >=0 allora c'è una @ e quindi va considerata come un possibile indirizzo e-mail; altrimenti va considerato come uno username
    }


    

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
		$_SESSION['mjc'] = $row['mjc'];
		$_SESSION['valuta'] = $row['valuta'];
		$_SESSION['billie_jean'] = $row['billie_jean'];
		$_SESSION['beat_it'] = $row['beat_it'];
		$_SESSION['rock_with_you'] = $row['rock_with_you'];
		$_SESSION['smooth_criminal'] = $row['smooth_criminal'];
		$_SESSION['thriller'] = $row['thriller'];
		$_SESSION['id'] = $row['id'];
		$_SESSION['immagine_profilo'] = $row['immagine_profilo'];
		$_SESSION['unlocked_billiejean'] = $row['unlocked_billiejean'];
        $_SESSION['unlocked_beatit'] = $row['unlocked_beatit'];
        $_SESSION['unlocked_smoothcriminal'] = $row['unlocked_smoothcriminal'];
		$_SESSION['unlocked_thriller'] = $row['unlocked_thriller'];
		$_SESSION['logged'] = true;

		header("Location: ../homepage/index.php");

	} else {
		$_SESSION['badpassword'] = true;
		header("Location: index.php");
	}


	 
	

?>
