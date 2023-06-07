<?php

class ShareListForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ShareListForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Compartilhar Lista");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Compartilhar Lista");


        $name = new TDBEntry('name', 'permission', 'SystemUsers', 'name','name asc'  );
        $email = new TDBEntry('email', 'permission', 'SystemUsers', 'email','email asc'  );
        $listId = new THidden('listId');

        $name->addValidation("Nome", new TRequiredValidator()); 
        $email->addValidation("Email", new TRequiredValidator()); 
        $email->addValidation("Email", new TEmailValidator(), []); 

        $name->setDisplayMask('{name}');
        $email->setDisplayMask('{email}');

        $listId->setSize(200);
        $name->setSize('100%');
        $email->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Nome:", '#FF0000', '14px', null, '100%'),$name]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("Email:", '#FF0000', '14px', null, '100%'),$email,$listId]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("O convidado receberá email com os dados de acesso a lista.", '#9E9E9E', '14px', null)]);
        $row3->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Compartilhar", new TAction([$this, 'onSave']), 'fas:share-alt #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); 
            $this->form->setData($data);

            TTransaction::open('permission');

            //get the company (SystemUnit) of the user
            $unit   = SystemUnit::find(TSession::getValue('userunitid'));
            $userId = TSession::getValue('userid');

            //check if the User's email was previously registered
            $systemUser = SystemUsers::newFromEmail($data->email);
            $password   = '';

            //if false, register the User
            if (!$systemUser)
            {
                //register the user
                $systemUser                 = new SystemUsers;
                $systemUser->name           = $data->name;
                $systemUser->login          = $data->email;
                $systemUser->email          = $data->email;    
                $systemUser->system_unit_id = $unit->id; //register the company (SystemUnit)
                $password                   = Util::generatePassword();
                $systemUser->password       = md5($password);
                $systemUser->active         = 'Y';
                $systemUser->frontpage_id   = 10; //WelcomeView
                $systemUser->store();

                //put the unit (company) on the user
                $systemUser->addSystemUserUnit($unit);
                //put the customer on the user's group
                $userGroup = SystemGroup::where('uuid', '=', '00f1b915-c4c6-482e-9a33-66ab49e50553')->first();
                $systemUser->addSystemUserGroup($userGroup);
            }  

            TTransaction::close();

            TTransaction::open('todolist');

            //check if the user is already link to List
            $check = TodolistUser::where('todolist_id', '=', $data->listId)
                                 ->where('user_id', '=', $systemUser->id)
                                 ->first();

            if(!$check)
            {
                //link user to list
                $listUser               = new TodolistUser;
                $listUser->todolist_id  = $data->listId;
                $listUser->user_id      = $systemUser->id;
                $listUser->store();
            }

            TTransaction::close();

            //prepare to send email
            $mailParam = ['email' => $data->email, 'password' => $password, 'listId' => $data->listId];
            $this->sendMail($mailParam);

            if($systemUser->id)
            {
                new TMessage('info', "Lista compartilhada com sucesso. <br>
                                      O convidado receberá email com os dados de acesso.");

                parent::closeWindow();
            }
            else
            {
                throw new Exception('Não foi possível realizar o compartilhamento, tente novamente ou fale com nosso suporte');
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

        $object         = new stdClass();
        $object->listId = $param['key'];
        TForm::sendData(self::$formName, $object);
    } 

    public  function sendMail($param = null) 
    {
        try 
        {
            //send email with generated password
            if(isset($param['email']) && isset($param['listId']))
            {
                TTransaction::open('todolist');
                $list = Todolist::find($param['listId']);
                TTransaction::close();

                $title    = TSession::getValue('username') . ' te enviou o compartilhamento da lista: ' . $list->name;

    				$subtitle = 'Agora você pode ajudar no gerenciamento da ToDo List, <br> 
    			             	acesse o sistema <a href = "https://todolist.ricardocamara.dev">todolist.ricardocamara.dev</a>';

    				$message  = "Login: <a href='#' style='text-decoration: none;'>{$param['email']}</a>";

	    			//on the first user registration your password will be sent in the body email
	    			if($param['password'])
	    			{
	    			    $message .= "<br> Senha: {$param['password']}";
	    			}
	
	    			PrepMailService::send([$param['email']], 'ToDo List - lista compartilhada: ' . $list->name, $title, $subtitle, $message);
            }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

