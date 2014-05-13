<?php
/**
 * 
 * Carrega la plantilla d'index, a més d'encarregar-se de certes funcions sobre el registre i la sessió de l'usuari
 * A més, serveix com a model del controlador Contact
 * 
 * @author Ferran <feerraan@gmail.com>
 */

class IndexController extends ControllerBase{
    protected $model;
    protected $view;
    //private $conf;
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
        //carregar la configuració
        $this->model= new IndexModel($arr);
        $this->view=new View();
        $this->view->setProp($this->model->getDataout());
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/index.html');
        $this->view->render();
    }
    public function index(){}
    
    /**
     * Permet a l'usuari iniciar sessió 
     */
    function login(){
        if(isset($_POST['email'])){
            $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $password=md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            $user=$this->model->login($email,$password);
            if ($user== TRUE){
                $this->Redirect('index');
                // cap a la pàgina principal
                 //header('Location:'.APP_W.'/index/index');
            }
            else{
                print("<br /><script>$('.regindex').fadeIn();alert('Usuari Incorrecte');</script>");
                //header('Location:'.APP_W.'/index/register');
            }
        }
    }
    
    /**
     * Permet a l'usuari tancar sessió 
     */
    function logout() {
        $this->model->logout();
        $this->Redirect('index');
    }
    
    /**
     * Permet a l'usuari registrar-se a la web
     */
    function registre() {
        $nom=filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $cognoms=filter_input(INPUT_POST, 'cognoms', FILTER_SANITIZE_STRING);
        $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $password=md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        
        $this->model->registre($nom,$cognoms,$email,$password);
        
        //$this->Redirect('index');
    }
} 
       


