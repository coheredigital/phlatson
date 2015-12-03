<?php

class Session extends App implements IteratorAggregate
{

    private $name;

    function __construct()
    {
        if($this->exists()){
            $this->start();
        }
        unregister_GLOBALS();

        // check for a logged in user
        if ($username = $this->get('_user_name')) {
            $user = $this->api('users')->get($username);
            // update timestamp to extend session life
            if ($user) {
                $this->set('_user_ts', time());
            }
            // set current user to the logged in user
        } else {
            $user = $this->api('users')->get("guest");
        }

        $this->api('user', $user);

    }


    public function exists(){
        if( isset($_SESSION) || $_COOKIE[$this->api('config')->sessionName] ){
            return true;
        }
        return false;
    }

    public function set($key, $value)
    {

        if(!$this->exists()) $this->start();

        $_SESSION["$this->className"][$key] = $value;
        return $this;
    }

    /**
     * Gets a session / flash variable
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {

        $value = $this->has($key) ? $_SESSION["$this->className"][$key] : null;
        // check if the key is a flash variable and remove if it is
        if ($this->isFlash($key) && !is_null($value)) {
            $this->remove($key);
            $this->removeFlash($key);
        }
        return $value;
    }

    /**
     * Get all session variables
     * @return array
     */
    public function all()
    {
        return $_SESSION["$this->className"];
    }

    /**
     * Checks for the existence of a session variable
     * Particularly useful for flash variables, where calling get will unset them
     *
     * @param  string $key the session key to check for
     * @return boolean      isset() result
     */
    public function has($key)
    {
        return isset($_SESSION["$this->className"][$key]);
    }

    /**
     *
     * Sets a read-once flash value on the segment or convert an existing session variable into a flash variable if no value supplied
     * @param string $key The key for the flash value.
     * @param mixed $value The value for the flash.
     *

     */
    public function flash($key, $value = null)
    {

        if($value){
            // set the value in session
            $this->set($key, $value);
        }

        if($this->has($key)){
            $_SESSION["$this->className"]["_flash"][$key] = 1;
        }

    }


    /**
     * determine if $key exists in flash session settings
     * @param  string $key
     * @return boolean
     */
    public function hasFlash($key)
    {
        return isset($_SESSION["$this->className"]["_flash"][$key]);
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
     * Removes / cancels an existing flash session key
     * Session varible will go back to being a regular session variable,
     * and will remain in session until it expires
     * @param  string $key
     * @return $this
     */
    public function removeFlash($key)
    {
        unset($_SESSION["$this->className"]["_flash"][$key]);
        return $this;
    }

    /**
     * Removes an existing session key
     * @param  string $key
     * @return $this
     */
    public function remove($key)
    {
        unset($_SESSION["$this->className"][$key]);
        return $this;
    }

    /**
     * Getter / Setter allow object like access ($session->variable)
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }


    /**
     * start the session, provide for syntactical convenience
     */
    protected function start()
    {
        session_name($this->api('config')->sessionName);
        @session_start();
    }

    /**
     *
     * Regenerates and replaces the current session id
     * @return bool True is regeneration worked, false if not.
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

    /**
     * get/set current session name
     * @see session_name()
     */
    public function name($name = null)
    {
        return session_name($name);
    }


    public function login($name, $password)
    {
        // should sanitize name
        $user = $this->api('users')->get("$name");
        if (!$user instanceof User) {
            throw new FlatbedException("User {$name} not found!");
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
        $sessionName = $this->name();
        $this->clear();


        if (isset($_COOKIE[$sessionName])) {

            $sessionTime = time() - 42000;

            setcookie($sessionName, '', $sessionTime, '/');
        }


        // end the current session
        $this->destroy();


        return $this;
    }

    public function getIterator()
    {
        return new ArrayObject($_SESSION["$this->className"]);
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