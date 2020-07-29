<?php
namespace Phlatson;
class Session extends Phlatson implements \IteratorAggregate
{

    protected $name;

    function __construct(string $name)
    {

        $this->name = $name;

        if($this->exists()){
            $this->start();
        }

        // check for a logged in user
        if ($username = $this->get('_user_name')) {
            $user = $this->api('users')->get($username);

            // update timestamp to extend session life
            if ($user) {
                $this->set('_user_ts', time());
            }
            
        } else {
            $user = $this->api('users')->get("guest");
        }

        // set current user found user
        $this->api('user', $user);


    }

    // check that a valid session still exists
    public function exists(){
        if( isset($_SESSION) || $_COOKIE[$this->name] ){
            return true;
        }
        return false;
    }

    public function set( string $name, $value)
    {

        if(!$this->exists()) $this->start();

        $_SESSION["$this->name"][$name] = $value;
        return $this;
    }

    /**
     * Gets a session / flash variable
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {

        $value = $this->has($name) ? $_SESSION["$this->name"][$name] : null;
        // check if the key is a flash variable and remove if it is
        if ($this->isFlash($name) && !is_null($value)) {
            $this->remove($name);
            $this->removeFlash($name);
        }
        return $value;
    }

    /**
     * Get all session variables
     * @return array
     */
    public function all()
    {
        return $_SESSION["$this->name"];
    }

    /**
     * Checks for the existence of a session variable
     * Particularly useful for flash variables, where calling get will unset them
     *
     * @param  string   $name the session key to check for
     * @return boolean  isset() result
     */
    public function has($name)
    {
        return isset($_SESSION["$this->name"][$name]);
    }

    /**
     *
     * Sets a read-once flash value on the segment or convert an existing session variable into a flash variable if no value supplied
     * @param string $name The key for the flash value.
     * @param mixed $value The value for the flash.
     *
     */
    public function flash($name, $value = null)
    {

        if($value){
            // set the value in session
            $this->set($name, $value);
        }

        if($this->has($name)){
            $_SESSION["$this->name"]["_flash"][$name] = 1;
        }

    }


    /**
     * determine if $key exists in flash session settings
     * @param  string $key
     * @return boolean
     */
    public function hasFlash($key)
    {
        return isset($_SESSION["$this->name"]["_flash"][$key]);
    }


    /**
     * Checks if session varaible is defined as a flash variable
     * @param string $key The key for the flash value.
     * @return mixed The flash value.
     */
    public function isFlash($key)
    {
        if ($this->hasFlash($key) && $this->has($key)) {
            return true;
        }
        return false;
    }

    /**
     * Removes existing flash session key
     * Session varible will go back to being a regular session variable,
     * and will remain in session until it expires
     * @param  string $key
     * @return $this
     */
    public function removeFlash($key)
    {
        unset($_SESSION["$this->name"]["_flash"][$key]);
        return $this;
    }

    /**
     * Removes an existing session key
     * @param  string $key
     * @return $this
     */
    public function remove($key)
    {
        unset($_SESSION["$this->name"][$key]);
        return $this;
    }


    public function __set( string $key, $value)
    {
        return $this->set($key, $value);
    }


    /**
     * start the session, provide for syntactical convenience
     */
    protected function start()
    {
        session_name($this->name);
        @session_start();
    }

    /**
     *
     * Regenerates and replaces the current session id
     * @return bool true is regeneration worked, false if not.
     *
     */
    public function regenerate()
    {
        return session_regenerate_id(true);
    }

    /**
     * clear all session variables
     */
    public function clear()
    {
        session_unset();
    }

    /**
     * destroy / ends the current session
     */
    public function destroy()
    {
        session_destroy();
    }


    public function login($name, $password)
    {
        // should sanitize name
        $user = $this->api('users')->get("$name");
        if (!$user instanceof User) {
            throw new \Exception("User {$name} not found!");
        }

        if ($user->authenticate($password)) {
            $this->regenerate(); // rebuild session data
            $this->set('_user_name', $user->name);
            $this->set('_user_time', time());
            $this->api('user', $user);
            return true;
        }
        return null;
    }

    public function logout()
    {
        $this->clear();

        if (isset($_COOKIE[$this->name])) {

            $sessionTime = time() - 42000;

            setcookie($this->name, '', $sessionTime, '/');
        }


        // end the current session
        $this->destroy();


        return $this;
    }

    public function getIterator()
    {
        return new ArrayObject($_SESSION["$this->name"]);
    }

    /**
     * Returns the current session status:
     * @return int
     * @see session_status()
     */
    public function getStatus()
    {
        return session_status();
    }

}