<?php
/**
 * Description of Bootstrap
 * Inicia el sistema
 * És una classe singleton que controla el fluxe del programa
 * 
 * @author Ferran <feerraan@gmail.com>
 */

class Bootstrap {
    protected $controller;
    protected $action;
    protected $params;
    protected $body;
    
    static $instace;
    
    /*
     * Per obtenir instancia
     * 
     * @return self::$instace
     */
    public static function getInstance(){
        if (!(self::$instace instanceof self)){
            self::$instace=new self();
        }
        return self::$instace;
        }
        

    /*
     * Agafa de la url el controlador i el model
     */
   private function __construct() {
            $request = filter_input(INPUT_SERVER,'REQUEST_URI',FILTER_DEFAULT);
            $parts=explode('/',trim($request,'/'));
            //treiem part del nom d'aplicació
            array_shift($parts);
            
            $this->controller=!empty($parts[0])?$parts[0]==="index.php"?DEF_CONTROLLER:$parts[0]:DEF_CONTROLLER;
            $this->action=!empty($parts[1])?$parts[1]:DEF_ACTION;
            // completem un array associatiu amb el paràmetres.
            if (!empty($parts[2])){
                $keys=$values=array();
                for($i=2,$cnt=count($parts);$i<$cnt;$i++){
                    if($i%2==0){
                        // si és parell és una clau
                        $keys[]=$parts[$i];
                    }
                    else{
                        //és imparell és un valor
                        $values[]=$parts[$i];
                    }
                }
               
                   $this->params=  array_combine($keys, $values);
                
            }
        }
        
        /*
         * Busca si existeix el controlador i els seus metodes, i si no el troba dona error
         */
        public function route(){
            $classe=ucfirst(strtolower($this->getController())).'Controller';
            if (class_exists($classe)){
                $routeCont=new ReflectionClass($classe);
                if($routeCont->hasMethod($this->getAction())){
                    $controller=$routeCont->newInstance($this->params);
                    $method=$routeCont->getMethod($this->getAction());
                    $method->invoke($controller);
                }else{
                    //throw new Exception("No Action");
                    $controller= new ErrorController(array('Error'=>' No action'));
                    $controller->index();
                    
                }
            }else{
                //throw new Exception("No Controller");
                $controller= new ErrorController(array('Error'=>' No controller'));
                $controller->index();
                
                }
            
        }
       /*
        * Obté el controlador
        * 
        * @return String $this->controller
        */
        public function getController(){
            return $this->controller;
        }
        
       /*
        * Obté l'acció
        * 
        * @return String $this->action
        */
        public function getAction(){
            return $this->action;
        }
       /*
        * Obté els paràmetres
        * 
        * @return array $this->params
        */
        public function getParams(){
            return $this->params;
                    
        }
    
}
