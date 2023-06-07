<?php

class ItemKanbanView extends TPage
{
    private static $database = 'todolist';
    private static $activeRecord = 'Item';
    private static $primaryKey = 'id';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        try
        {
            parent::__construct();

            $kanban = new TKanban;
            $kanban->setItemDatabase(self::$database);

            $criteriaStage = new TCriteria();
            $criteriaItem = new TCriteria();

            $criteriaStage->setProperty('order', 'list_order asc');
            $criteriaItem->setProperty('order', 'item_order asc');

            $filterVar = TSession::getValue("userunitid");
            $criteriaStage->add(new TFilter('company_id', '=', $filterVar)); 

            TTransaction::open(self::$database);
            $stages = Todolist::getObjects($criteriaStage);
            $items  = Item::getObjects($criteriaItem);

            if($stages)
            {
                foreach ($stages as $key => $stage)
                {

                    $kanban->addStage($stage->id, "{name}", $stage ,$stage->color);

                }    
            }

            if($items)
            {
                foreach ($items as $key => $item)
                {

                    $kanban->addItem($item->id, $item->todolist_id, "{name}", "", '', $item);

                }    
            }

            $kanbanStageAction_TodolistFormView_onShow = new TAction(['TodolistFormView', 'onShow']);

            $kanban->addStageAction("Detalhes da lista", $kanbanStageAction_TodolistFormView_onShow, 'fas:eye #2196F3');
            $kanbanStageAction_TodolistForm_onEdit = new TAction(['TodolistForm', 'onEdit']);

            $kanban->addStageAction("Editar lista", $kanbanStageAction_TodolistForm_onEdit, 'far:edit #2196F3');
            $kanbanStageAction_ItemKanbanView_onDeleteList = new TAction(['ItemKanbanView', 'onDeleteList']);

            $kanban->addStageAction("Excluir lista", $kanbanStageAction_ItemKanbanView_onDeleteList, 'fas:trash-alt #F44336');
            $kanbanStageAction_ShareListForm_onShow = new TAction(['ShareListForm', 'onShow']);

            $kanban->addStageAction("Compartilhar lista", $kanbanStageAction_ShareListForm_onShow, 'fas:share-alt #009688');

            $kanbanStageShortcut_ItemKanbanView_onAddItem = new TAction(['ItemKanbanView', 'onAddItem']);

            $kanban->addStageShortcut("Adicionar Item", $kanbanStageShortcut_ItemKanbanView_onAddItem, 'fas:plus #506283');

            $kanbanItemAction_ItemForm_onEdit = new TAction(['ItemForm', 'onEdit']);

            $kanban->addItemAction("Editar", $kanbanItemAction_ItemForm_onEdit, 'far:edit #2196F3', null, true);
            $kanbanItemAction_ItemKanbanView_onDeleteItem = new TAction(['ItemKanbanView', 'onDeleteItem']);

            $kanban->addItemAction("excluir", $kanbanItemAction_ItemKanbanView_onDeleteItem, 'fas:trash-alt #F44336', null, true);

            //$kanban->setTemplatePath('app/resources/card.html');

            $kanban->setItemDropAction(new TAction([__CLASS__, 'onUpdateItemDrop']));
            $kanban->setStageDropAction(new TAction([__CLASS__, 'onUpdateStageDrop']));
            TTransaction::close();

            $container = new TVBox;

            $container->style = 'width: 100%';
            $container->class = 'form-container';
            if(empty($param['target_container']))
            {
                $container->add(TBreadCrumb::create(["ToDo List","Listas"]));
            }
            $container->add($kanban);

            parent::add($container);
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onDeleteItem($param = null) 
    {
        try 
        {
            // define the delete action
            $action = new TAction(array($this, 'deleteItem'));
            $action->setParameters($param); // pass the key paramseter ahead
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onDeleteList($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $list = Todolist::find($param['key']);
            TTransaction::close();

            //if the user is not the owner, it cannot delete list
            if($list->owner_id != TSession::getValue('userid'))
            {
                throw new Exception('Apenas o proprietário pode deletar a lista');
            }

            // define the delete action
            $action = new TAction(array($this, 'deleteList'));
            $action->setParameters($param); // pass the key paramseter ahead
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onAddItem($param = null) 
    {
        try 
        {
            $pageParam = [ 'list_id' => $param['key'] ];
            TApplication::loadPage('ItemForm', 'onEdit', $pageParam);
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onUpdateStageDrop($param)
    {
        try
        {
            TTransaction::open(self::$database);

            if (!empty($param['order']))
            {
                foreach ($param['order'] as $key => $id)
                {
                    $sequence = ++ $key;

                    $stage = new Todolist($id);
                    $stage->list_order = $sequence;

                    $stage->store();

                }
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    /**
     * Update item on drop
     */
    public static function onUpdateItemDrop($param)
    {
        try
        {
            TTransaction::open(self::$database);

            if (!empty($param['order']))
            {
                foreach ($param['order'] as $key => $id)
                {
                    $sequence = ++$key;

                    $item = new Item($id);
                    $item->item_order = $sequence;
                    $item->todolist_id = $param['stage_id'];

                    $item->store();

                }

                TTransaction::close();
            }
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
    public function onShow($param = null)
    {
		if(!empty($param['novaLista']))
		{
			TApplication::gotoPage('TodolistForm', 'onEdit', ['novaLista' => $param['novaLista']]);
		}
    } 
    
    public static function onLoad($param = null)
    {
		TApplication::gotoPage('ItemKanbanView', 'onShow', ['novaLista' => '']);
    } 

    public  function deleteList($param = null) 
    {
        try 
        {
            // get the paramseter $key
            $key = $param['key'];
            // open a transaction with database
            TTransaction::open(self::$database);

            // instantiates object
            $object = new Todolist($key, false);

            //deletes the associates items
            Item::where('todolist_id', '=', $object->id)->delete();

            //deletes the associates users
            TodolistUser::where('todolist_id', '=', $object->id)->delete();

            // deletes the object from the database
            $object->delete();

            // close the transaction
            TTransaction::close();

            // shows the success message
            TToast::show('success', AdiantiCoreTranslator::translate('Record deleted'), 'topRight', 'far:check-circle');

            TApplication::loadPage('ItemKanbanView', 'onShow', []);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function deleteItem($param = null) 
    {
        try 
        {
            // get the paramseter $key
            $key = $param['key'];
            // open a transaction with database
            TTransaction::open(self::$database);

            // instantiates object
            $object = new Item($key, false);

            // deletes the object from the database
            $object->delete();

            // close the transaction
            TTransaction::close();

            // shows the success message
            TToast::show('success', AdiantiCoreTranslator::translate('Record deleted'), 'topRight', 'far:check-circle');

            TApplication::loadPage('ItemKanbanView', 'onShow', []);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

