<?php

class ObjectCollectionFilter extends FilterIterator
{

    public function __construct(Iterator $collection , $filter )
    {
        parent::__construct($collection);
    }

    public function accept()
    {
        $object = $this->getInnerIterator()->current();
        if( $object->setting('hidden')) return false;
        return true;
    }


    // /**
    //  * returns self with a limit set for pagination
    //  * @return $this
    //  */
    // public function limit(int $limit) : self
    // {
    //     if ($limit < 0) {
    //         throw new FlatbedException("Limit cannot be set to less than 0");
    //     }
    //     $this->limit = $limit;
    //     $this->endIndex = $this->limit - 1;

    //     return $this;
    // }


    // /**
    //  * returns self with a limit set for pagination
    //  * @return $this
    //  */
    // public function paginate($pageNumber) : self
    // {

    //     if ($this->limit ===  0) {
    //         throw new FlatbedException("Must set a limit on ObjectCollection before pagination can be used.");
    //     }

    //     $this->isPaginated = true;
    //     // TODO : set $name to paramter, this is the get variable to use for paginating

    //     // determine the page count
    //     $count = $this->count();
    //     $this->pageCount = intval($count / $this->limit);
    //     if($count % $this->limit > 0) $this->pageCount++;

    //     $this->currentPage = 1;

    //     // overwrite current page base on request page
    //     if ( $pageNumber && $this->limit ) {
    //         $this->currentPage = $pageNumber;
    //     }

    //     $this->endIndex = $this->currentPage * $this->limit;

    //     return $this;
    // }


}
