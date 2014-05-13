<?php
/**
 * Model per a error
 *
 * @author Ferran <feerraan@gmail.com>
 */

class ErrorModel extends Model{
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    function __construct($arr) {
        parent::__construct($arr);
    }
}

