<?php

class Page extends DataObject implements ViewableObject
{

    const DATA_FOLDER = 'pages';
    protected $parent;

    function __construct($file = null)
    {

        if ( $parent = $this->getParentFromFile( $file ) ) {
            $this->parent = $parent;
        }

        parent::__construct($file);

    }

    /**
     * override of Object::getUrl because page are accessed from the
     * site root URL and note under /site/pages/
     * @return string 
     */
    public function getUrl(): string
    {
        $url = parent::getUrl();
        // TODO :  improve this, too 'hard coded'
        return str_replace("/site/pages", "", $url);
    }


    /**
     * @return string  path to the current object data file
     */
    public function getPath(): string
    {
        $path = '';
        if ( !$this->isNew() ) {
            $path = dirname( $this->file );
        }
        else{
            if ( $this->parent instanceof Page) {
                $path = $this->parent->getPath() . $this->name;
            }
            else {
                $path = SITE_PATH . $this->name;
            }
        }

        return Filter::path($path);
        return $path;
    }


    protected function getParentPathFromFile($file)
    {
        $path = dirname($file); // file path
        $path = dirname($path); // path parent
        return $path;
    }

    protected function getParentFromFile($file)
    {
        $path = $this->getParentPathFromFile($file);

        $parent = $this->api('pages')->getByPath($path);

        return $parent;
    }

    public function parent()
    {

        $parents = new Page();
        // start with current page
        $parentPath = $this->getParentPath();
        $parent = $this->api("pages")->getByPath($parentPath);

        // get parent and set current page as parent until no parents exist
        return $parent;
    }

    public function setParent( $parent )
    {

        $this->parent = $parent;

        // get parent and set current page as parent until no parents exist
        return $this;
    }

    /**
     * return an ObjectCollections contain this Objects
     * parent and each succesive after that
     *
     * @return ObjectCollection
     */
    public function parents() : ObjectCollection
    {

        $parents = new ObjectCollection();
        // start with current page
        $page = $this;

        // get parent and set current page as parent until no parents exist
        while ($page = $page->parent) {
            $parents->prepend($page);
        }
        return $parents;
    }

    /**
     * returns highest level parent, or self if no other parent found
     * @return [type] [description]
     */
    public function rootParent()
    {
        $parents = $this->parents();
        if ($parents->count()) {
            return $parents->last();
        }
        else {
            return $this;
        }
    }

    // public function files()
    // {
    //     return new FileCollection($this);
    // }
    //
    // protected function images()
    // {
    //     return new ImageArray($this);
    // }

    public function children()
    {

        $children = new ObjectCollection();

        $folders = glob( $this->path . "*", GLOB_ONLYDIR | GLOB_NOSORT);

        foreach ($folders as $folder) {
            $page = $this->api("pages")->getByPath( $folder );
            if (!$page instanceof self) continue;
            $children->add($page);
        }

        return $children;
    }


    /**
     * temp solution to have "create" url in backend
     * TODO: replace
     * 
     * @param array
     * @return void
     */
    protected function createUrl(array $array): string
    {
        if (is_array($array) && implode("", $array)) {
            $url = "/" . implode("/", $array);
            return $url;
        }
        return '';
    }


    /**
     * @return bool
     */
    public function isViewable()
    {
        if($this->isSystem()) return false;
        if(!$this->template->view) return false;
        return true;
    }

    /**
     * enable convenient access to $page->template->view->render();
     * @return string decided by view file, typically HTML markup
     */
    public function _render(){        
        return $this->template->view->render($this);
    }

    /**
     * get variable overrides specifc to Page
     * @param  string $name the key / name being requested
     * @return mixed
     */
    public function get( string $name)
    {
        switch ($name) {
            case 'parent':
            case 'parents':
            case 'rootParent':
            case 'files':
            case 'images':
                return $this->{$name}();
            case 'objectType': // protected / private variable that should have public get
                return $this->{$name};
            default:
                return parent::get($name);
        }

    }


    public function set( string $name, $value )
    {

        // only allow values to be set for existing fields ??
        switch ($name) {
            case "parent":
                $this->setParent($value);
                break;
            case "template":
                parent::set($name, $value);
                break;
            default:
                if ($this->getTemplate() && $this->getTemplate()->fields && $this->getTemplate()->fields->has($name)) {
                    $field = $this->api("fields")->get("$name");
                    $fieldtype = $field->fieldtype;
                    $this->data[$name] = $fieldtype->getSave($value);
                } else {
                    parent::set($name, $value);
                }
        }

    }

}
