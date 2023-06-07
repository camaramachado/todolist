<?php

class TodolistForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'todolist';
    private static $activeRecord = 'Todolist';
    private static $primaryKey = 'id';
    private static $formName = 'form_TodolistWindowForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("ToDo List");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("ToDo List");


        $name = new TEntry('name');
        $color = new TColor('color');
        $id = new THidden('id');
        $owner_id = new THidden('owner_id');
        $company_id = new THidden('company_id');
        $uuid = new THidden('uuid');

        $name->addValidation("Lista", new TRequiredValidator()); 

        $id->setSize(200);
        $uuid->setSize(200);
        $color->setSize(150);
        $name->setSize('100%');
        $owner_id->setSize(200);
        $company_id->setSize(200);

        $name->autofocus = 'autofocus';

        $row1 = $this->form->addFields([new TLabel("Lista:", '#ff0000', '14px', null, '100%'),$name]);
        $row1->layout = ['col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("Cor:", null, '14px', null, '100%'),$color,$id,$owner_id,$company_id,$uuid]);
        $row2->layout = ['col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Todolist(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $userid = TSession::getValue('userid');

            //If it is the first registration (it does not have an id), 
            //it takes the id of the logged in user, the id of your company and generate uuid for the newest List
            if(!$data->id)
            {
                $object->owner_id   = $userid;
                $object->company_id = TSession::getValue('userunitid');
                $object->uuid       = uniqid();
            }

            $object->store(); // save the object 

            //check if the user is already link to List
            $check = TodolistUser::where('todolist_id', '=', $object->id)
                                 ->where('user_id', '=', $userid)
                                 ->first();

            if(!$check)
            {
                //link user to list
                $listUser               = new TodolistUser;
                $listUser->todolist_id  = $object->id;
                $listUser->user_id      = $userid;
                $listUser->store();
            }

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ItemKanbanView', 'onShow', $loadPageParam); 

                TWindow::closeWindow(parent::getId()); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
			if (isset($param['novaLista']))
            {
				$object 		  = new stdClass; // instantiates the Active Record 
				$object->name = $param['novaLista'];
                $this->form->setData($object); // fill the form 
			}            
            elseif(isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Todolist($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

