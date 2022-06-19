<?php

class SignupDB extends Dbh
{
    protected function setUser($userid, $pwd, $email)
    {
        // Dit maak een gebruiker aan in de databse.
        $stmt = $this->connect()->prepare('INSERT INTO medewerkers (user, pwd, email) VALUES (?, ?, ?);'); //input statement

        // password security
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute(array($userid, $hashedPwd, $email))) {
            $stmt = null;
            header("location: signin.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }


    protected function checkUser($userid, $email)
    {
        // Dit checkt de database voor gelijke data.
        $stmt = $this->connect()->prepare('SELECT user FROM medewerkers WHERE user = ? OR email = ?;'); //select statement

        if (!$stmt->execute(array($userid, $email))) {
            $stmt = null;
            header("location: signin.php?error=stmtfailed");
            exit();
        }

        $resultCheck;
        if ($stmt->rowCount() > 0) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }

        return $resultCheck;
    }
}