<?php
namespace Flatbed;
class ObjectCollectionPagination extends \LimitIterator
{

    protected $limit = -1;
    protected $pageCount;
    protected $currentPage;

    public function __construct(\Iterator $collection ,  int $offset = 0, int $limit = -1 )
    {

    	parent::__construct($collection, $offset, $limit);

        // set the page count
        $this->pageCount = $this->getPageCount($limit);
    }

    /**
     * returns the number of pages required based on the set limit
     * @return int
     */
    public function getPageCount(int $limit) : int
    {

    	$count = iterator_count($this->getInnerIterator());

    	$pageCount = intval( $count / $limit );
    	if($count % $limit > 0) $pageCount++;
    	return $pageCount;
    }

    public function get($name)
    {
        switch ($name) {
            case 'className':
                return get_class($this);
            case 'pageCount':
                return $this->{$name};
            default:
                return $this->collection[$name];
        }
    }


    /**
     * returns self with a limit set for pagination
     * @return $this
     */
    public function paginate($pageNumber) : self
    {

        if ($this->limit <  1) {
            throw new FlatbedException\FlatbedException("Must set a limit on ObjectCollection before pagination can be used.");
        }

        $this->isPaginated = true;
        // TODO : set $name to paramter, this is the get variable to use for paginating

        // set the page count
        $this->pageCount = $this->getPageCount();

        $this->currentPage = 1;

        // overwrite current page base on request page
        if ( $pageNumber && $this->limit ) {
            $this->currentPage = $pageNumber;
        }

        $this->endIndex = $this->currentPage * $this->limit;

        return $this;
    }


}
