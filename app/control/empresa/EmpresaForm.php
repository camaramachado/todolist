<?php

class EmpresaForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_EmpresaForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Cadastre-se");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastre-se");


        $name = new TEntry('name');
        $email = new TEntry('email');
        $cpf_cnpj = new TEntry('cpf_cnpj');

        $name->addValidation("Nome", new TRequiredValidator()); 
        $email->addValidation("Email", new TRequiredValidator()); 
        $cpf_cnpj->addValidation("CPF/CNPJ", new TRequiredValidator()); 

        $cpf_cnpj->setMask('9!');
        $name->setSize('100%');
        $email->setSize('100%');
        $cpf_cnpj->setSize('100%');

        $name->placeholder = "Nome";
        $email->placeholder = "Email";
        $cpf_cnpj->placeholder = "CPF/CNPJ";


        $row1 = $this->form->addFields([new TLabel(new TImage('fas:info-circle #F76E56')."Para ter acesso ao ToDo List cadastre-se informando os dados abaixo.", '#9E9E9E', '16px', null, '100%')]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([$name]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([$email]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([$cpf_cnpj]);
        $row4->layout = [' col-sm-12'];

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
            $data = $this->form->getData(); 
            $this->form->setData($data);

            //validate CPF/CNPJ
            if( strlen($data->cpf_cnpj) == 11)
            {
                (new TCPFValidator())->validate('CPF', $data->cpf_cnpj);
            }
            else
            {
                (new TCNPJValidator())->validate('CNPJ', $data->cpf_cnpj);
            }

            TTransaction::open('permission');

            //check if there is SystemUnit with same cpf_cnpj
            $test = SystemUnit::where('name', '=', $data->cpf_cnpj)->first();

            if($test)
            {
                throw new Exception('Já existe uma unidade com este CPF/CNPJ');
            }

            //check if the login was previously registered
            if (SystemUsers::newFromLogin($data->email) instanceof SystemUsers)
            {
                throw new Exception('Já existe um usuário cadastrado com este email. Se esta conta for sua vá para a tela de login, caso contrário informe outro email.');
            }

            //check if the mail was previously registered
            if (SystemUsers::newFromEmail($data->email) instanceof SystemUsers)
            {
               throw new Exception('Já existe uma empresa cadastrada com este email. Se esta conta for sua vá para a tela de login, caso contrário informe outro email.');
            }  

            //register the company (SystemUnit)
            $unit       = new SystemUnit();
            $unit->name = $data->cpf_cnpj;
            $unit->store();

            //get the default input screen
            $screen = SystemProgram::where('controller', '=', 'ItemKanbanView')->first();

            //register the user
            $systemUser                 = new SystemUsers;
            $systemUser->name           = $data->name;
            $systemUser->login          = $data->email;
            $systemUser->email          = $data->email;    
            $systemUser->system_unit_id = $unit->id;
            $password                   = Util::generatePassword();
            $systemUser->password       = md5($password);
            $systemUser->active         = 'Y';
            $systemUser->frontpage_id   = $screen->id; //ItemKanbanView
            $systemUser->store();

            //put the unit (company) on the user
            $systemUser->addSystemUserUnit($unit);
            //put the customer on the user's group
            $userGroup = SystemGroup::where('uuid', '=', '00f1b915-c4c6-482e-9a33-66ab49e50553')->first();
            $systemUser->addSystemUserGroup($userGroup);

            TTransaction::close();

            //prepare to send email
            $mailParam = ['email' => $data->email, 'password' => $password];
            $this->sendMail($mailParam);

            if($systemUser->id)
            {
                new TMessage('info', "Seu cadastro foi realizado com sucesso. <br>
                                      Você receberá email com os dados de acesso.");
                
                parent::closeWindow();
            }
            else
            {
                throw new Exception('Não foi possível realizar o cadastro, tente novamente ou fale com nosso suporte');
            }

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

    } 

    public  function sendMail($param = null) 
    {
        try 
        {
            //send email with generated password
            if(isset($param['password']))
            {
                $title    = 'Seu Login e Senha do sistema e aplicativo';

    			$subtitle = 'Informamos que seus dados foram cadastrados com sucesso, 
    			             você pode acessar o sistema <a href = "https://todolist.ricardocamara.dev">todolist.ricardocamara.dev</a> <br>
    			             Seus dados de acesso devem ser mantidos em sigilo. <br>
    			             Recomendamos trocar a senha assim que fizer o primeiro login no sistema.';

    			$message  = "Login: <a href='#' style='text-decoration: none;'>{$param['email']}</a> <br>
    						 Senha: {$param['password']}";

    			PrepMailService::send([$param['email']], 'Seja bem vindo ao ToDo List', $title, $subtitle, $message);
            }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

