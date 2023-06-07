<?php

class ItemForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'todolist';
    private static $activeRecord = 'Item';
    private static $primaryKey = 'id';
    private static $formName = 'form_ItemForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Item");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Item");


        $name = new TEntry('name');
        $id = new THidden('id');
        $todolist_id = new THidden('todolist_id');
        $item_order = new THidden('item_order');

        $name->addValidation("Item", new TRequiredValidator()); 

        $id->setSize(200);
        $name->setSize('100%');
        $item_order->setSize(200);
        $todolist_id->setSize(200);


        $row1 = $this->form->addFields([new TLabel("Item:", '#ff0000', '14px', null, '100%'),$name,$id,$todolist_id,$item_order]);
        $row1->layout = ['col-sm-12'];

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
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            (new TRequiredValidator())->validate('Lista', $data->todolist_id); //validate if todolist_id is setted

            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $object = new Item(); // create an empty object 

            $object->fromArray( (array) $data); // load the object with data

            //get the next item_order in the first register
            if(!$object->id)
            {
                $last               = Item::where('todolist_id', '=', $object->todolist_id)->last();
                $object->item_order = ($last) ? $last->item_order + 1 : 1;
            }

            $object->store(); // save the object 

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
          if (isset($param['list_id']))
            {
                $object              = new stdClass;
                $object->todolist_id = $param['list_id'];
                $this->form->setData($object); // fill the form 
            }
            elseif (isset($param['key']))
            {
                TTransaction::open(self::$database); // open a transaction

                $key    = $param['key'];  // get the parameter $key
                $object = new Item($key); // instantiates the Active Record 

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

