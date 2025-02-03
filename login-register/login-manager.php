<?php

    require_once "config.php";

    function controlloInput($input) {       //vado a vedere se l'input ricevuto è uno username o una e-mail.

        return strpos($input,"@");       //se è >=0 allora c'è una @ e quindi va considerata come un possibile indirizzo e-mail; altrimenti va considerato come uno username
    }


    $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());


	$password = $_POST["passwordlogin"];

	if (controlloInput($_POST["usernamelogin"]) > -1) {		//ho inserito in questo campo una e-mail

		$email = pg_escape_string($db,$_POST["usernamelogin"]);

		$sql = "SELECT * FROM utenti WHERE email = $1 ";

		$ret = pg_prepare($db,"selezionaUtente",$sql);

		if (!$ret) {
			echo "Errore durante il riconoscimento utente tramite e-mail :" . pg_last_error($db);
		} else {

				$ret = pg_execute($db,"selezionaUtente",array($email));

				if (pg_num_rows($ret) == 0) {

					echo "Errore: L'email $email non è associata ad alcun account";

				} else {
					
					$row = pg_fetch_assoc($ret);

					if ( password_verify($password,$row['password']) ) {

						session_start();

						$_SESSION['username'] = $row['username'];

						header("Location: ../homepage/UtenteRegistrato.html");
					}

					else {
						echo "Errore: Password errata";
					}

			}


		}

	} else {		//ho inserito uno username

		$username = pg_escape_string($db,$_POST["usernamelogin"]);

		$sql = "SELECT * FROM utenti WHERE username = $1 ";

		$ret = pg_prepare($db,"selezionaUtente",$sql);

		if (!$ret) {
			echo "Errore durante il riconoscimento utente tramite username :" . pg_last_error($db);
		} else {

			$ret = pg_execute($db,"selezionaUtente",array($username));

			if (pg_num_rows($ret) == 0) {

				echo "Errore: Utente $username non trovato";

			} else {

				$row = pg_fetch_assoc($ret);

				if ( password_verify($password,$row['password']) ) {

					session_start();

					$_SESSION['username'] = $row['username'];

					header("Location: ../homepage/UtenteRegistrato.html");

				}

				else {
					
					echo "Errore: Password errata";
				}

			}
		}

	}
	

?>