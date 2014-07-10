<?php

class Session implements IteratorAggregate
{


    function __construct()
    {
        $this->start();
        unregister_GLOBALS();

        // check for a logged in user
        if ($username = $this->get('_user_name')) {
            $user = api::get('users')->get($username);
            // update timestamp to extend session life
            if ($user) {
                $this->set('_user_ts', time());
            }
            // set current user to the logged in user
        } else {
            $user = api::get('users')->get("guest");
        }
        api::get('users')->setActiveUser($user);

    }

    public function set($key, $value)
    {

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
        return $_SESSION[$this->className()];
    }

    /**
     * Checks for the existence of a session variable
     * @param  string $key the session key to check for
     * @return boolean      isset() result
     */
    public function has($key)
    {
        return isset($_SESSION["$this->className"][$key]);
    }

    /**
     *
     * Sets a read-once flash value on the segment.
     * @param string $key The key for the flash value.
     * @param mixed $value The value for the flash.
     */
    public function flash($key, $value)
    {
        // set the value in session
        $this->set($key, $value);

        // the flash bool in _flash array
        $this->setFlash($key);
    }

    /**
     * convert an existing session variable into a flash varaible
     * will be removed on next access
     * @param [type] $key [description]
     */
    public function setFlash($key)
    {
        if ($this->has($key)) {
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
     * redirects page using PHP header
     * @param  string or Page object  $url   redirect to url from root or to page
     * @param  boolean $permanent is redirect permanent?
     */
    public function redirect($value, $permanent = true)
    {
        $url = $value instanceof Page ? $value->url : $value;
        // perform the redirect
        if ($permanent) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: $url");
        header("Connection: close");
        exit(0);
    }

    /**
     * start the session, provide for syntactical convenience
     */
    protected function start()
    {
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
        $user = api::get('users')->get("$name");
        if (!$user instanceof User) {
            throw new Exception("User {$name} not found!");
        }

        if ($user->authenticate($password)) {
            $this->regenerate(); // rebuild session data
            $this->set('_user_name', $user->name);
            $this->set('_user_time', time());
            api::get('user', $user);
        }
        return null;
    }

    public function logout()
    {
        $sessionName = $this->name();
        $this->clear();

        if (isset($_COOKIE[$sessionName])) {
            setcookie($sessionName, '', time() - 42000, '/');
        }


        // end the current session
        $this->destroy();
        $this->name($sessionName);
        $this->start();
        $this->regenerate();

        $this->clear();
        $guest = api::get('users')->get("guest");
        api::get('user', $guest);

        return $this;
    }

    public function getIterator()
    {
        return new ArrayObject($_SESSION[$this->className()]);
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