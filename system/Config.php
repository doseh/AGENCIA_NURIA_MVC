<?php
/**
 * Description of Config
 * Registry de les configuracions
 * @author Ferran <feerraan@gmail.com>
 */
class Config {
    private $data=array();
    
    static $instance;
    public static $database=array();
    /*
     * Obté la instancia
     * 
     * @return self::$instance
     */
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance=new self();
            return self::$instance;
        }
        else{ return self::$instance;
            
        }
    }
    
    /*
     * Diu que data es de tipus array
     */
    private function __construct() {
        $this->data=array();
    }
    
    /*
     * Afegeix una clau i un valor en un array associatiu
     * 
     * @params String $key
     * @params String $var
     * 
     * @return true
     */
    function __set($key, $var) {
    $this->data[$key] = $var;
    return true;
  }

    /*
     * Mitjançant la clau, retorna el valor corresponent
     * 
     * @params String $key
     * 
     * @return $this->data[$key]
     * @return null
     */
  function __get($key) {
    if (isset($this->data[$key]) == false) {
      return null;
    }
    return $this->data[$key];
  }

    /*
     * Buida la part de l'array corresponent a la $data introduida
     *  
     * @params array $data
     */
  function __unset($data) {
    unset($this->data[$key]);
  }
  
  /*
   * Per obtenir l'array sencer
   * 
   * @return array $this->data
   */
    public function getData(){
        return $this->data;
    }
    
    /*
     * Copia els valors d'un array json i els introdueix en un altre array associatiu
     */
    function JSON(){
        $arr_json=json_decode(file_get_contents(APP.'Config.json'));
        foreach ($arr_json as $key=>$value) {
            $this->data[$key] = $value;
        }
    }
}
