<?php


class Users extends ObjectArray
{

    protected $root = "users/";
    protected $singularName = "User";


    public function setActiveUser(User $user)
    {
        $this->setApi('user', $user);
    }

}