<?php

class User extends Object
{

    protected $rootFolder = "users/";

    /**
     * is this the guest user?
     * @return boolean checks if name === guest
     */
    public function isGuest()
    {
        return $this->get("name") == "guest";
    }

    /**
     * check if user logged in
     * @return boolean opposite of isGuest() result
     */
    public function isLoggedin()
    {
        return !$this->isGuest();
    }


    public function authenticate($pass)
    {
        /*  Check if string passed matches a hashed version of the stored password
            so we can determine that the password has not been hashed yet, but avoid
            a possible brute force string match to the hash
        */
        $passGet = $this->get("password");
        $hash = password_hash($passGet, PASSWORD_BCRYPT);
        if (password_verify($pass, $hash)) {
            $this->password = $hash;
            $this->save();
        }

        return password_verify($pass, $this->get("password"));

    }


    public function get($string)
    {
        switch ($string) {
            case 'template':
                return api("templates")->get("user");
                break;
            default:
                return parent::get($string);
                break;
        }
    }


}