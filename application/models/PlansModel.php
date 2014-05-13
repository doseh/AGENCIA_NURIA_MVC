<?php
/**
 *  Model per als vols
 *
 * @author Ferran <feerraan@gmail.com>
 */

class PlansModel extends Model{
    
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
    }
}
