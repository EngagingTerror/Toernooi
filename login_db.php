<?php

class LoginDB extends Dbh
{
    protected function getUser($userid, $pwd)
    {
        // Kijkt of gebruiker al bestaat.
        $stmt = $this->connect()->prepare('SELECT pwd FROM medewerkers WHERE user = ? or email = ?;'); //select statement

        if (!$stmt->execute(array($userid, $pwd))) {
            $stmt = null;
            header("location: login.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: login.php?error=usernotfound");
            exit();
        }

        // zoek of username, email en password gelijk is.
        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["pwd"]);

        if ($checkPwd == false) {
            $stmt = null;
            header("location: login.php?error=wrongpassword");
            exit();
        } elseif ($checkPwd == true) {
            $stmt = $this->connect()->prepare('SELECT * FROM medewerkers WHERE user = ? or email = ? AND pwd = ?;');

            if (!$stmt->execute(array($userid, $userid, $pwd))) {
                $stmt = null;
                header("location: login.php?error=stmtfailed");
                exit();
            }

            if ($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: login.php?error=usernotfound");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["userid"] = $user[0]["medewerker_id"];
            $_SESSION["useruserid"] = $user[0]["medewerker_user"];

            $stmt = null;
        }
    }
}