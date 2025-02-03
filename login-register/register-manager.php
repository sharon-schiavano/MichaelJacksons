<?php

    require_once "config.php";

    $db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());



	if( !empty($_POST) && $_POST['registrati'] )    {	

		$username = pg_escape_string($db, $_POST["usernameregister"]);	//evitiamo l'SQL injection

		$email = pg_escape_string($db, $_POST["email"]);

        $password = password_hash($_POST["passwordregister"],PASSWORD_DEFAULT);		//più sicurezza sulla password con l'hashing


		$sql = "INSERT INTO utenti(username, email, password)
				VALUES($1, $2, $3)";

		$ret = pg_prepare($db,"inserisciUtente", $sql); 

		

		if (!$ret) {

			echo "Errore durante la registrazione: " . pg_last_error($db); 

		} 

		else {

			$ret = pg_execute($db, "inserisciUtente", array($username, $email, $password));

			if(!$ret){
				echo pg_last_error($db);
			}

			else{
				header("location: mj_login.html");
				echo "Aggiunto al database $username!";

			}

		}
	}

?>