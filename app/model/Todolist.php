<?php

class Todolist extends TRecord
{
    const TABLENAME  = 'todolist';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}
    const CACHECONTROL  = 'TAPCache';

    private $owner;
    private $company;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('owner_id');
        parent::addAttribute('company_id');
        parent::addAttribute('uuid');
        parent::addAttribute('prior_list_id');
        parent::addAttribute('list_order');
        parent::addAttribute('color');
    
    }

    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_owner(SystemUsers $object)
    {
        $this->owner = $object;
        $this->owner_id = $object->id;
    }

    /**
     * Method get_owner
     * Sample of usage: $var->owner->attribute;
     * @returns SystemUsers instance
     */
    public function get_owner()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->owner))
            $this->owner = new SystemUsers($this->owner_id);
        TTransaction::close();
        // returns the associated object
        return $this->owner;
    }
    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_company(SystemUnit $object)
    {
        $this->company = $object;
        $this->company_id = $object->id;
    }

    /**
     * Method get_company
     * Sample of usage: $var->company->attribute;
     * @returns SystemUnit instance
     */
    public function get_company()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->company))
            $this->company = new SystemUnit($this->company_id);
        TTransaction::close();
        // returns the associated object
        return $this->company;
    }

    /**
     * Method getTodolistUsers
     */
    public function getTodolistUsers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('todolist_id', '=', $this->id));
        return TodolistUser::getObjects( $criteria );
    }
    /**
     * Method getItems
     */
    public function getItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('todolist_id', '=', $this->id));
        return Item::getObjects( $criteria );
    }

    public function set_todolist_user_todolist_to_string($todolist_user_todolist_to_string)
    {
        if(is_array($todolist_user_todolist_to_string))
        {
            $values = Todolist::where('id', 'in', $todolist_user_todolist_to_string)->getIndexedArray('name', 'name');
            $this->todolist_user_todolist_to_string = implode(', ', $values);
        }
        else
        {
            $this->todolist_user_todolist_to_string = $todolist_user_todolist_to_string;
        }

        $this->vdata['todolist_user_todolist_to_string'] = $this->todolist_user_todolist_to_string;
    }

    public function get_todolist_user_todolist_to_string()
    {
        if(!empty($this->todolist_user_todolist_to_string))
        {
            return $this->todolist_user_todolist_to_string;
        }
    
        $values = TodolistUser::where('todolist_id', '=', $this->id)->getIndexedArray('todolist_id','{todolist->name}');
        return implode(', ', $values);
    }

    public function set_item_todolist_to_string($item_todolist_to_string)
    {
        if(is_array($item_todolist_to_string))
        {
            $values = Todolist::where('id', 'in', $item_todolist_to_string)->getIndexedArray('name', 'name');
            $this->item_todolist_to_string = implode(', ', $values);
        }
        else
        {
            $this->item_todolist_to_string = $item_todolist_to_string;
        }

        $this->vdata['item_todolist_to_string'] = $this->item_todolist_to_string;
    }

    public function get_item_todolist_to_string()
    {
        if(!empty($this->item_todolist_to_string))
        {
            return $this->item_todolist_to_string;
        }
    
        $values = Item::where('todolist_id', '=', $this->id)->getIndexedArray('todolist_id','{todolist->name}');
        return implode(', ', $values);
    }

    /**
     * method get_prior_list_name()
     * Retorns the name of the prior list, if it exists
     * @returns string
     */
    public function get_prior_list_name()
    {
        if($this->prior_list_id)
        {
            return Todolist::find($this->prior_list_id)->name;
        }
    }
        
}

