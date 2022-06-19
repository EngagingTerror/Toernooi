<?php

class LoginCheck extends LoginDB
{

    private $userid;
    private $pwd;

    public function __construct($userid, $pwd)
    {
        $this->userid = $userid;
        $this->pwd = $pwd;
    }

    public function loginUser()
    {
        if ($this->emptyInput() == false) {
            // echo "Empty input!";
            header("location: login.php?error=emptyinput");
            exit();
        }

        // als er geen error komt. Wordt de gebruiker gewoon ingelogd met de gegevens bij die in de  database staan.
        $this->getUser($this->userid, $this->pwd);
    }


    //checkt of niks leeg is.
    private function emptyInput()
    {
        $result;
        if (empty($this->userid) || empty($this->pwd)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}