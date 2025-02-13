<?php
$host = 'localhost';
$port = '5432';
$dbname = 'gruppo07';
$user = 'www';
$password = 'tw2024';

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

$db = pg_connect($connection_string) or die('Impossibile connettersi al database: ' . pg_last_error());
