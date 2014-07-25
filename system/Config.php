<?php

class Config extends Object
{

    function __construct()
    {

        $this->styles = new SimpleArray();
        $this->scripts = new SimpleArray();

        // setup default urls / paths
        $this->setupDirectories();

        // load site config
        $this->getData("{$this->paths->site}config.json");

        // add admin url for convenience
        $this->urls->admin = $this->urls->root . $this->adminUrl;

        /*
         * Output errors if debug true, else disable error reporting
         */
        if ($this->debug) {
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('xdebug.var_display_max_depth', '10');
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        /*
         * Prepare any PHP ini_set options
         *
         */
        session_name($this->sessionName);

        ini_set('session.use_cookies', true);
        ini_set('session.use_only_cookies', 1);
        ini_set("session.gc_maxlifetime", $this->sessionExpireSeconds);
        ini_set("session.save_path", rtrim($this->paths->assets . DIRECTORY_SEPARATOR . "sessions", '/'));
        ini_set("date.timezone", $this->timezone);
        ini_set('default_charset', 'utf-8');

    }


    protected function getData($file)
    {
        if (is_file($file)) {
            $this->path = normalizePath(str_replace(Object::DEFAULT_SAVE_FILE,"",$file));
            $this->data = array_merge($this->data, json_decode(file_get_contents($file), true));
        }
    }

    protected function setupDirectories(){

        // store an array of directories
        $directories = array();
        $directories['assets'] = 'assets/';
        $directories['site'] = 'site/';
        $directories['pages'] = $directories['site'] . 'pages/';
        $directories['fields'] = $directories['site'] . 'fields/';
        $directories['templates'] = $directories['site'] . 'templates/';
        $directories['extensions'] = $directories['site'] . 'extensions/';
        $directories['layouts'] = $directories['site'] . 'layouts/';
        $directories['users'] = $directories['site'] . 'users/';


        /*
         * Setup configuration data and default paths/urls
         */

        $urls = new Paths(ROOT_URL);
        // loop through directories and set key / value
        foreach ($directories as $key => $value) {
            $urls->{$key} = $value;
        }

        // clone the urls object and change the root
        $paths = clone $urls;
        $paths->root = ROOT_PATH;

        // add the urls anc paths to config
        $this->urls = $urls;
        $this->paths = $paths;




    }


    public function get($name)
    {
        if($this->has($name)){
            return $this->getUnformatted($name);
        }
        else{
            return parent::get($name);
        }

    }


}