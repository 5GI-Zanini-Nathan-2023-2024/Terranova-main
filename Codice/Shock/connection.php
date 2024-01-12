<?php

// Credenziali necessarie per permettere la connessione al server MySQL:
$servername = "sql11.freemysqlhosting.net";
$username = "sql11676321";
$password = "IGFdaKyX1Q";

// Creazione della connessione:
$connection = new mysqli($servername, $username, $password);
$dbName = "sql11676321";

// Controllo della connessione:
if ($connection->connect_error) {
  die("Connessione fallita: " . $connection->connect_error);
}

// Selezione del database su cui verranno effettuate le successive operazioni:
mysqli_select_db($connection, $dbName);

?>