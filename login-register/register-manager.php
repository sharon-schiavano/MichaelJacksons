<?php

    require_once "config.php";

	session_start();

	if( !empty($_POST) && $_POST['registrati'] )    {	

		$username = pg_escape_string($db, $_POST["usernameregister"]);	//evitiamo l'SQL injection

		$email = pg_escape_string($db, $_POST["email"]);

		$_SESSION['oldusername'] = $username;	//salvo gli ultimi valori inseriti nel form per renderlo sticky

		$_SESSION['oldemail'] = $email;		

        $password = password_hash($_POST["passwordregister"],PASSWORD_DEFAULT);		//più sicurezza sulla password con l'hashing

		//PRIMA DI REGISTRARE IL NUOVO UTENTE...
		$sql = "SELECT * FROM utenti WHERE username = $1";		//verifico che non sia già presente nel db questo username

		$ret = pg_prepare($db,"ricercaUsername",$sql);

		if (!$ret) {

			echo "Errore durante la preparazione della query: " . preg_last_error(); 
			exit;
		} 

		else {

			$ret = pg_execute($db,"ricercaUsername",array($username));

			if (pg_num_rows($ret) != 0)	{	//username già presente nel db: impossibile registrare il nuovo utente

				$_SESSION['existentusername'] = true;

				header("location: index.php");
				exit;

			}
		}

		$sql = "SELECT * FROM utenti WHERE email = $1";		//verifico che non sia già presente nel db questo indirizzo e-mail

		$ret = pg_prepare($db,"ricercaEmail",$sql);

		if (!$ret) {

			echo "Errore durante la preparazione della query: " . preg_last_error(); 
			exit;
		} 

		else {

			$ret = pg_execute($db,"ricercaEmail",array($username));

			if (pg_num_rows($ret) != 0)	{	//email già presente nel db: impossibile registrare il nuovo utente

				$_SESSION['existentemail'] = true;

				header("location: index.php");
				exit;

			}
		}

		


		//REGISTRAZIONE NUOVO UTENTE
		$sql = "INSERT INTO utenti(username, email, password)
				VALUES($1, $2, $3)";

		$ret = pg_prepare($db,"inserisciUtente", $sql); 	

		if (!$ret) {

			echo "Errore durante la preparazione della query: " . preg_last_error(); 
			exit;
		} 

		else {

			$ret = pg_execute($db, "inserisciUtente", array($username, $email, $password));

			if(!$ret){
				echo "Errore: impossibile completare la registrazione di questo account!" . pg_last_error($db);
				exit;
			}

			else{
				$_SESSION['registered'] = true;
				header("location: index.php");
			}

		}
	}

	pg_close($db);

?>