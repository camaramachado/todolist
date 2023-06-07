<?php

class SobreForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_SobreForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Sobre");


        $logo = new TImage('app/images/builderLayoutIcon.jpg');
        $criar_lista3 = new TImage('app/images/sobre/criar_lista3.png');
        $criar_lista2 = new TImage('app/images/sobre/criar_lista2.png');
        $criar_lista = new TImage('app/images/sobre/criar_lista.png');
        $editar_lista = new TImage('app/images/sobre/editar_lista.png');
        $criar_lista22 = new TImage('app/images/sobre/criar_lista2.png');
        $excluir_lista = new TImage('app/images/sobre/excluir_lista.png');
        $compartilhar_lista = new TImage('app/images/sobre/compartilhar_lista.png');
        $compartilhar_lista2 = new TImage('app/images/sobre/compartilhar_lista2.png');
        $adicionar_item = new TImage('app/images/sobre/adicionar_item.png');
        $adicionar_item2 = new TImage('app/images/sobre/adicionar_item2.png');


        $logo->width = '140px';
        $logo->height = '140px';
        $criar_lista->width = '250px';
        $criar_lista3->width = '700px';
        $criar_lista3->height = '50px';
        $criar_lista2->width = '650px';
        $criar_lista->height = '380px';
        $editar_lista->width = '400px';
        $criar_lista2->height = '350px';
        $editar_lista->height = '300px';
        $criar_lista22->width = '650px';
        $excluir_lista->width = '400px';
        $criar_lista22->height = '350px';
        $excluir_lista->height = '300px';
        $adicionar_item->width = '400px';
        $adicionar_item->height = '400px';
        $adicionar_item2->width = '650px';
        $adicionar_item2->height = '300px';
        $compartilhar_lista->width = '400px';
        $compartilhar_lista->height = '300px';
        $compartilhar_lista2->width = '650px';
        $compartilhar_lista2->height = '350px';

        $this->logo = $logo;
        $this->criar_lista3 = $criar_lista3;
        $this->criar_lista2 = $criar_lista2;
        $this->criar_lista = $criar_lista;
        $this->editar_lista = $editar_lista;
        $this->criar_lista22 = $criar_lista22;
        $this->excluir_lista = $excluir_lista;
        $this->compartilhar_lista = $compartilhar_lista;
        $this->compartilhar_lista2 = $compartilhar_lista2;
        $this->adicionar_item = $adicionar_item;
        $this->adicionar_item2 = $adicionar_item2;

        $row1 = $this->form->addFields([$logo,new TLabel("Crie, edite e compartilhe suas listas de forma simples!", '#9E9E9E', '16px', null, '100%')]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row3 = $this->form->addFields([new TLabel("Criando uma lista", null, '14px', 'B')],[new TLabel("Ao entrar com uma URL qualquer, a mesma deve ser usada para se referenciar a um novo ToDo List.", null, '14px', null, '100%'),new TLabel("Inclua o nome da lista que se quer criar ao final da expressão: <strong>&novaLista=</strong>", null, '14px', null, '100%'),$criar_lista3,new TLabel("Neste exemplo utilizamos: <strong>&novaLista=Folha de pagamento</strong>", null, '14px', null, '100%')]);
        $row3->layout = [' col-sm-4',' col-sm-8'];

        $row4 = $this->form->addFields([],[new TLabel("<br>Vai aparecer uma janela, informe a lista, opcionalmente pode escolher uma cor pra facilitar na sua identificação. Clique no botão <strong>Salvar</strong>.", null, '14px', null, '100%'),$criar_lista2]);
        $row4->layout = [' col-sm-4',' col-sm-8'];

        $row5 = $this->form->addFields([],[new TLabel("Outra forma de criar lista, no menu clique no menu <strong>+ Nova Lista</strong>", null, '14px', null, '100%'),$criar_lista]);
        $row5->layout = [' col-sm-4',' col-sm-8'];

        $row6 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row7 = $this->form->addFields([new TLabel("Editando a lista", null, '14px', 'B')],[new TLabel("Clique no menu suspenso da lista, depois clique em <strong>Editar lista</strong>", null, '14px', null, '100%'),$editar_lista]);
        $row7->layout = [' col-sm-4',' col-sm-8'];

        $row8 = $this->form->addFields([],[new TLabel("Vai aparecer a janela de edição com o nome da lista, opcionalmente pode escolher uma cor pra facilitar na sua identificação. Clique no botão <strong>Salvar</strong>.", null, '14px', null, '100%'),$criar_lista22]);
        $row8->layout = [' col-sm-4',' col-sm-8'];

        $row9 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row10 = $this->form->addFields([new TLabel("Excluindo a lista", null, '14px', 'B')],[new TLabel("Clique no menu suspenso da lista, depois clique em <strong>Excluir lista</strong>", null, '14px', null, '100%'),$excluir_lista,new TLabel("Vai abrir uma janela, confirme a exclusão clicando no <strong>Sim</strong>.", null, '14px', null, '100%')]);
        $row10->layout = [' col-sm-4',' col-sm-8'];

        $row11 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row12 = $this->form->addFields([new TLabel("Compartilhamento da lista", null, '14px', 'B', '100%'),new TLabel("As listas podem ser facilmente compartilhadas com outros usuários", null, '14px', null, '100%')],[new TLabel("Clique no menu suspenso da lista, depois clique em <strong>Compartilhar lista</strong>.", null, '14px', null, '100%'),$compartilhar_lista]);
        $row12->layout = [' col-sm-4',' col-sm-8'];

        $row13 = $this->form->addFields([],[new TLabel("Vai abrir uma janela, informe o nome e email do usuário que irá colaborar com sua lista. Depois clique no botão <strong>Compartilhar</strong>.", null, '14px', null, '100%'),$compartilhar_lista2,new TLabel("O novo colaborador receberá email com os dados de acesso a lista.", null, '14px', null, '100%')]);
        $row13->layout = [' col-sm-4',' col-sm-8'];

        $row14 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row15 = $this->form->addFields([new TLabel("Adicionando um item na lista", null, '14px', 'B', '100%')],[new TLabel("Na parte inferior da lista clique em <strong>+ Adicionar Item</strong>", null, '14px', null, '100%'),$adicionar_item]);
        $row15->layout = [' col-sm-4',' col-sm-8'];

        $row16 = $this->form->addFields([],[new TLabel("Vai aparecer uma janela, informe o item. Clique no botão <strong>Salvar</strong>.", null, '14px', null, '100%'),$adicionar_item2]);
        $row16->layout = [' col-sm-4',' col-sm-8'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Sobre","Sobre"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onShow($param = null)
    {               

    } 

}

