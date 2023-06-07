<?php

class Item extends TRecord
{
    const TABLENAME  = 'item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}
    const CACHECONTROL  = 'TAPCache';

    private $todolist;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('todolist_id');
        parent::addAttribute('name');
        parent::addAttribute('prior_item_id');
        parent::addAttribute('item_order');
    
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
     * method get_prior_item_name()
     * Retorns the name of the prior item, if it exists
     * @returns string
     */
    public function get_prior_item_name()
    {
        if($this->prior_item_id)
        {
            return Item::find($this->prior_item_id)->name;
        }
    }

}

