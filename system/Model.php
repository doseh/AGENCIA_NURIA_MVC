<?php
/**
 * Model: proporciona eines d'accés bàsic a DADES
 *
 * @author Ferran <feerraan@gmail.com>
 */
class Model {
    /**
     *
     * @datain array , per comunicar amb BBDD
     * @dataout array, per comunicar amb controller
     * 
     */
    protected $db;
    protected $stmt;
    protected $config;
    protected $datain=array();
    protected $dataout=array();
    
    /*
     * Diu quins son els atribut de seva propia classe
     * 
     * @param array $arr
     */
    function __construct($arr) {
        $this->db=SPDO::singleton();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->datain=array();
        $this->dataout=$arr;
         //parametres de configuració
        $this->datain=$this->config;
        //afegir en DataOut els paràmetres URI
        $this->addDataout($arr);

    }
    
    /*
     * Dona el valor de l'array a datain
     * 
     * @param array $arr
     */
    public function setDatain($arr){
        if (isset($arr)){
            $this->datain=$arr;
        }
    }
    /*
     * Dona el valor de l'array a dataout
     * 
     * @param array $arr
     */
    public function setDataout($arr){
         if (isset($arr)){
            $this->dataout=$arr;
        }
    }
    /**
     * Metode per afegir arrays de comunic amb controller
     * 
     * @param type $arr
     */
    public function addDataout($arr){
        if ($arr || $this->dataout){
            $this->dataout=  array_merge($this->dataout,$arr);
        }else{
            $this->dataout=$arr;
        }
    }
    /*
     * Per obtenir datain
     * 
     * @return array datain
     */
    public function getDatain(){
        return $this->datain;
    }
    /*
     * Per obtenir dataout
     * 
     * @return array dataout
     */
    public function getDataout(){
        return $this->dataout;
    }
    /**
     * funcions associades a l'us de la base de dades
     */
    /**
     * Consulta segura
     * @param type $sql
     */
     public function query($sql, $values = array()){
        $_result = false;
        if (($_stmt = $this->db->prepare($sql))) {
            if (preg_match_all('/(:\w+)/', $sql, $_named, PREG_PATTERN_ORDER)){
                $_named = array_pop($_named);
                foreach ($_named as $_param) {
                    $_stmt->bindValue($_param, $values[substr($_param, 1)]);
                }
            }
            try {
                if (! $_stmt->execute()) {
                    print_r($_stmt->errorInfo());
                }
            $_result = $_stmt->fetchAll(PDO::FETCH_ASSOC);
            $_stmt->closeCursor();
            } catch(PDOException $e){
                echo "Excepcio atrapada";
                print_r($e->getMessage());
            }
        }
      
        return $_result;
    }
    /*
     * @param String $spName
     * @param array $values
     * 
     * @return String $_rs
     */
   public function executeSP($spName, $values = array())
   {
      $_rs = $this->query('CALL ' . $spName, $values);

      return $_rs;
   }
}