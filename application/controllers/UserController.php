<?php
/**
 * Carrega la plantilla d'usuari
 *
 * @author Ferran <feerraan@gmail.com>
 */

class UserController extends ControllerBase{
    protected $model;
    protected $view;
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
        $this->model= new UserModel($arr);
        $this->view=new View();
        $this->view->setProp($this->model->getDataout());
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/user.html');
        $this->view->render();
    }
    public function index(){}
} 
       


