<?php
/* Databasereferenties. Ervan uitgaande dat u MySQL gebruikt
server met standaardinstelling (gebruiker 'root' zonder wachtwoord) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'toernooi');
 
/* Poging om verbinding te maken met MySQL-database*/
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Stel de PDO-foutmodus in op uitzondering
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>