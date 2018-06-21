<?php
namespace Phlatson;
class Page extends PhlatsonObject
{

    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    public $template;
    public $routes;

    protected $defaultFields = [
        "template"
    ];

    function __construct($path = null)
    {
        parent::__construct($path);
    }



}
