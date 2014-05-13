<?php
/**
 * Model per a hotels
 *
 * @author Ferran <feerraan@gmail.com>
 */

class HotelsModel extends Model{
    
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
    }
}
