<?php
namespace Phlatson;
class User extends DataObject
{

    protected $rootFolder = "users";

    public $defaultFields = [
        "name",
        "password",
        "role"
    ];

    protected $requiredFields = [
        "name",
        "password",
        "role"
    ];

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


    /**
     * 
     * Autheticate a user
     * 
     * First check is a hashed version of the password matches a hashed version
     * of the string stored in the users password field, so we can determine that 
     * the password has not been hashed yet, but avoid a possible brute force 
     * string match to the hash
     *
     * @param string $password
     * @return bool
     */
    public function authenticate( string $password): bool
    {
        
        // first hash the stored password
        $storedPasswordHash = password_hash( $this->get("password") , PASSWORD_DEFAULT);

        // match against entered password
        if (password_verify($password, $storedPasswordHash)) {
            // if a match is made we store this to the user 
            $this->set('password', $storedPasswordHash )->save();
        }

        // verify entered password
        return password_verify($password, $this->get("password"));

    }


    public function get( string $name)
    {
        switch ($name) {
            case 'template':
                $template = $this->api("templates")->get("user"); 
                // $template->master = $this;
                return $template;
            default:
                return parent::get($name);
        }
    }


}