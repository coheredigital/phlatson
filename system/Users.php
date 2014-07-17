<?php


class Users extends Objects
{

    protected $rootFolder = "users/";
    protected $singularName = "User";


    public function setActiveUser(User $user)
    {
        api::register('user', $user);
    }

}