<?php


// Publishers are able to notify subscribers of events
interface PublisherInterface
{
//    public function notify();
}

//// Abstraction for a class that needs to implements the Observer pattern
//abstract AbstractPublisher implements PublisherInterface
//{
//    private $observers;
//
//    public function notify()
//    {
//        foreach ($this->observers as $obs)
//        {
//            $obs->update($this);
//        }
//    }
//
//    public function addSubscriber(SubscriberInterface $sub)
//    {
//        $this->observers[] = $sub;
//    }
//}