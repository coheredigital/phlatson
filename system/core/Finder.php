<?php

class Finder extends RecursiveFilterIterator
{
//
//    protected $file;
//
//    public function __construct( $iterator, $file = "data.json")
//    {
//        $this->file = $file;
//        parent::__construct($iterator);
//
//    }
//
//    public function accept()
//    {
////        return true;
//        $current = $this->current();
//        return $this->hasChildren() || $current->isFile() && $current->getFilename() == $this->file;
//    }
//
//    public function getChildren() {
//
//        $array = $this->getInnerIterator()->getChildren();
//
//        return new self($array, $this->file);
//    }
//
//    public function __toString()
//    {
//        return $this->current()->getFilename();
//    }

}