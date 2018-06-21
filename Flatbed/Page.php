<?php
namespace Flatbed;
class Page extends FlatbedObject
{

    const DATA_FOLDER = 'pages';
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

    public function parent()
    {
    }

    /**
     * return an ObjectCollections contain this Objects
     * parent and each succesive after that
     *
     * @return ObjectCollection
     */
    public function parents() : ObjectCollection
    {
    }

    /**
     * returns highest level parent, or self if no other parent found
     * @return Object
     */
    public function rootParent() : ?Object
    {
    }

    public function children()
    {
    }

    public function render()
    {
    }

    public function get( string $name)
    {
    }


    public function set( string $name, $value )
    {
    }

}
