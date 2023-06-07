<?php

class TodolistFormView extends TWindow
{
    protected $form; // form
    private static $database = 'todolist';
    private static $activeRecord = 'Todolist';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Todolist';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        parent::setSize(0.50, null);
        parent::setTitle("Detalhes");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $todolist = new Todolist($param['key']);
        // define the form title
        $this->form->setFormTitle("Detalhes");

        $label2 = new TLabel("Lista:", '', '12px', '', '100%');
        $text2 = new TTextDisplay($todolist->name, '', '14px', 'B');
        $label8 = new TLabel("Cor:", '', '12px', '', '100%');
        $text8 = new TTextDisplay(new TImage('fas:square-full #9E9E9E').$todolist->color, '#9E9E9E', '12px', '');
        $label3 = new TLabel("Proprietário:", '', '12px', '', '100%');
        $text3 = new TTextDisplay($todolist->owner->name, '#9E9E9E', '12px', '');
        $label5 = new TLabel("uuid:", '', '12px', '', '100%');
        $text5 = new TTextDisplay($todolist->uuid, '#9E9E9E', '12px', '');


        $row1 = $this->form->addFields([$label2,$text2],[$label8,$text8]);
        $row1->layout = [' col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([$label3,$text3],[$label5,$text5]);
        $row2->layout = [' col-sm-6',' col-sm-6'];

        $row3 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $this->item_todolist_id_list = new TQuickGrid;
        $this->item_todolist_id_list->disableHtmlConversion();
        $this->item_todolist_id_list->style = 'width:100%';
        $this->item_todolist_id_list->disableDefaultClick();

        $column_name = $this->item_todolist_id_list->addQuickColumn("Itens", 'name', 'left');

        $this->item_todolist_id_list->createModel();

        $criteria_item_todolist_id = new TCriteria();
        $criteria_item_todolist_id->add(new TFilter('todolist_id', '=', $todolist->id));

        $criteria_item_todolist_id->setProperty('order', 'item_order asc');

        $item_todolist_id_items = Item::getObjects($criteria_item_todolist_id);

        $this->item_todolist_id_list->addItems($item_todolist_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add(new BootstrapDatagridWrapper($this->item_todolist_id_list));
        $panel->add($tableResponsiveDiv);

        $this->form->addContent([$panel]);
        $row4 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);

        $this->todolist_user_todolist_id_list = new TQuickGrid;
        $this->todolist_user_todolist_id_list->disableHtmlConversion();
        $this->todolist_user_todolist_id_list->style = 'width:100%';
        $this->todolist_user_todolist_id_list->disableDefaultClick();

        $column_user_name = $this->todolist_user_todolist_id_list->addQuickColumn("Usuários", 'user->name', 'left');
        $column_user_email = $this->todolist_user_todolist_id_list->addQuickColumn("", 'user->email', 'left');

        $this->todolist_user_todolist_id_list->createModel();

        $criteria_todolist_user_todolist_id = new TCriteria();
        $criteria_todolist_user_todolist_id->add(new TFilter('todolist_id', '=', $todolist->id));

        $criteria_todolist_user_todolist_id->setProperty('order', 'id asc');

        $todolist_user_todolist_id_items = TodolistUser::getObjects($criteria_todolist_user_todolist_id);

        $this->todolist_user_todolist_id_list->addItems($todolist_user_todolist_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add(new BootstrapDatagridWrapper($this->todolist_user_todolist_id_list));
        $panel->add($tableResponsiveDiv);

        $this->form->addContent([$panel]);


        TTransaction::close();
        parent::add($this->form);

    }

    public function onShow($param = null)
    {     

        $object         = new stdClass();
        $object->listId = $param['key'];
        TForm::sendData(self::$formName, $object);
    }

}

