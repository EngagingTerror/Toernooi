<?php

class Dbh
{
    // properties worden hier gemaakt
    private $servername;
    private $username;
    private $pwd;
    private $dbname;
    private $charset;

    protected function connect()
    {
        // Deze funtion geeft connectie met de database.
        $this->servername = "localhost";
        $this->username = "root";
        $this->pwd = "";
        $this->dbname = "toernooi";
        $this->charset = "utf8mb4";

        // try catch method kijkt voor errors  in de connectie met mysql
        try {
            $dsn = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $pdo = new PDO($dsn, $this->username, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "No database connection. Failed:" . $e->getMessage() . "<br/>";
            die();
        }
    }
}