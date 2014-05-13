<?php
/**
 *  Model per a l'usuari
 *
 * @author Ferran <feerraan@gmail.com>
 */

class UserModel extends Model{
    
    private $user;
    
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
    }
    
}