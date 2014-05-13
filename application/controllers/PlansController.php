<?php
/**
 * Carrega la plantilla de plans
 *
 * @author Ferran <feerraan@gmail.com>
 */

class PlansController extends ControllerBase{
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
        $this->model= new PlansModel($arr);
        $this->view=new View();
        $this->view->setProp($this->model->getDataout());
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/plans.html');
        $this->view->render();
    }
    public function index(){}
    
} 
       


