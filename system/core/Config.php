<?php

class Config extends Object
{
//
//    protected $urls;
//    protected $paths;

    function __construct()
    {
        // TODO : add support for proper root request without assuming a blank request is a root request, possibly turn requests into object where each level is retrieval and the original string etc.
        // default to using the name when no url parameter passed

        $this->load("C:\\xampp2\\htdocs\\xpages\\site\\config.json");
        $this->setupDirectories();

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


    protected function load($file)
    {
        if (is_file($file)) {
            $this->path = realpath(str_replace(Object::DATA_FILE,"",$file));
            $this->data = json_decode(file_get_contents($file), true);
        }
    }

    protected function setupDirectories(){

        // start an array of directories
        $directories = array();
        // site directories
        $directories['site'] = 'site/';
        $directories['assets'] = 'assets/';
        $directories['cache'] = $directories['assets'] . 'cache/';
        $directories['pages'] = $directories['site'] . 'pages/';
        $directories['fields'] = $directories['site'] . 'fields/';
        $directories['templates'] = $directories['site'] . 'templates/';
        $directories['extensions'] = $directories['site'] . 'extensions/';
        $directories['layouts'] = $directories['site'] . 'layouts/';
        $directories['users'] = $directories['site'] . 'users/';

        // system directories
        $directories['system'] = 'system/';
        $directories['core'] = $directories['system'] . 'core/';
        // system alternatives
        $directories['systemPages'] = $directories['system'] . 'pages/';
        $directories['systemLayouts'] = $directories['system'] . 'layouts/';
        $directories['systemFields'] = $directories['system'] . 'fields/';
        $directories['systemTemplates'] = $directories['system'] . 'templates/';
        $directories['systemExtensions'] = $directories['system'] . 'extensions/';



        if (isset($_SERVER['HTTP_HOST'])) {
            $httpHost = strtolower($_SERVER['HTTP_HOST']);
            $rootURL = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/\\") . '/';
        } else {
            $httpHost = '';
            $rootURL = '/';
        }


        /*
         * Setup configuration data and default paths/urls
         */

        $urls = new Paths($rootURL);
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


        // HTTPS and AJAX
        $this->https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
        $this->ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

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