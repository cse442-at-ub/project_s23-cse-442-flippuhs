<?php

class User {
    private $firstname;
    private $lastname;
    private $email;
    private $zipcode;

    function __construct($firstname,$lastname,$email,$zipcode)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->zipcode = $zipcode;
    }

    function getFirstName() {
        return $this->firstname;
    }

    function getLastName() {
        return $this->lastname;
    }

    function getEmail() {
        return $this->email;
    }

    function getZipcode() {
        return $this->zipcode;
    }
}

?>