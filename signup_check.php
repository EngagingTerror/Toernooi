<?php

class SignupCheck extends SignupDB
{

    private $userid;
    private $pwd;
    private $pwd2;
    private $email;

    public function __construct($userid, $pwd, $pwd2, $email)
    {
        $this->userid = $userid;
        $this->pwd = $pwd;
        $this->pwd2 = $pwd2;
        $this->email = $email;
    }

    public function signupUser()
    {
        if ($this->emptyInput() == false) {
            // echo "Empty input!";
            header("location: signin.php?error=emptyinput");
            exit();
        }

        if ($this->invalidEmail() == false) {
            // echo "Invalid Email!";
            header("location: signin.php?error=email");
            exit();
        }

        if ($this->pwdMatch() == false) {
            // echo "Password doesn't match!";
            header("location: signin.php?error=passwordDONTmatch");
            exit();
        }

        if ($this->useridTaken() == false) {
            // echo "Username or email taken!";
            header("location: signin.php?error=username_emailtaken");
            exit();
        }

        // als er geen error komt. Wordt de gebruiker aangemaakt bij de database. linked to signup_db.php
        $this->setUser($this->userid, $this->pwd, $this->email,);
    }


    //check of niks leeg is.
    private function emptyInput()
    {
        $result;
        if (empty($this->userid) || empty($this->pwd) || empty($this->pwd2) || empty($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // check of er een goede email is gezet.
    private function invalidEmail()
    {
        $result;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // check of pwd is gelijk aan pwd2.
    private function pwdMatch()
    {
        $result;
        if ($this->pwd !== $this->pwd2) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // check voor bestaande users.
    private function useridTaken()
    {
        $result;
        if (!$this->checkUser($this->userid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}