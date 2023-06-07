<?php

class TodolistUser extends TRecord
{
    const TABLENAME  = 'todolist_user';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}
    const CACHECONTROL  = 'TAPCache';

    private $todolist;
    private $user;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('todolist_id');
        parent::addAttribute('user_id');
            
    }

    /**
     * Method set_todolist
     * Sample of usage: $var->todolist = $object;
     * @param $object Instance of Todolist
     */
    public function set_todolist(Todolist $object)
    {
        $this->todolist = $object;
        $this->todolist_id = $object->id;
    }

    /**
     * Method get_todolist
     * Sample of usage: $var->todolist->attribute;
     * @returns Todolist instance
     */
    public function get_todolist()
    {
    
        // loads the associated object
        if (empty($this->todolist))
            $this->todolist = new Todolist($this->todolist_id);
    
        // returns the associated object
        return $this->todolist;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_user(SystemUsers $object)
    {
        $this->user = $object;
        $this->user_id = $object->id;
    }

    /**
     * Method get_user
     * Sample of usage: $var->user->attribute;
     * @returns SystemUsers instance
     */
    public function get_user()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->user))
            $this->user = new SystemUsers($this->user_id);
        TTransaction::close();
        // returns the associated object
        return $this->user;
    }

    
}

