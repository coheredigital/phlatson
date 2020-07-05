<?php
namespace Phlatson;

abstract class ObjectFinder extends Phlatson
{

    const RETURN_TYPE = null;
    private Finder $finder;

    final public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * get the singular object type by it uri/name
     * @param  string $name the name or uri that points to the object relative to its storage folder
     * @return Object
     */
    public function get(string $url) : ?Object
    {
        return $this->finder->getType(self::RETURN_TYPE, $url);
    }

}
