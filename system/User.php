<?php

class User extends Object
{

    protected $rootFolder = "users";
    public $defaultFields = ["password"];
    protected $requiredElements = ["password","role"];

    /**
     * is this the guest user?
     * @return boolean checks if name === guest
     */
    public function isGuest()
    {
        return $this->name == "guest";
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
        $hash = password_hash($passGet, PASSWORD_DEFAULT);
        if (password_verify($pass, $hash)) {
            $this->password = $hash;
            $this->save();
        }

        return password_verify($pass, $this->get("password"));

    }


    public function get($name)
    {
        switch ($name) {
            case 'template':
                $template = $this->api("templates")->get("user"); //  TODO : refactor - the method for defining the master to this template is done manually here, maybe I can automate this like with pages
                $template->master = $this;
                return $template;
            default:
                return parent::get($name);
        }
    }


}