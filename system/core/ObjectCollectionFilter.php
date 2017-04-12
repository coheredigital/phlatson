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


}
