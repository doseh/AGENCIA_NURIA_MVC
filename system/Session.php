<?php
/**
 * Conte les funcions sobre la sesió d'un usuari
 * 
 * @author Ferran <feerraan@gmail.com>
 */

class Session
{
    /*
     * Inicia sessió
     */
    public static function init()
    {
        session_start();
    }
    
    /*
     * Tanca sessió
     * 
     * @params String $key = false
     */
    public static function destroy($key = false)
    {
        if($key){
            if(is_array($key)){
                for($i = 0; $i < count($key); $i++){
                    if(isset($_SESSION[$key[$i]])){
                        unset($_SESSION[$key[$i]]);
                    }
                }
            }
            else{
                if(isset($_SESSION[$key])){
                    unset($_SESSION[$key]);
                }
            }
        }
        else{
            session_destroy();
        }
    }
    
    /*
     * Afegeix una clau amb el seu valor a la sessió
     * 
     * @params String $clave
     * @params String $valor
     */
    public static function set($clave, $valor)
    {
        if(!empty($clave)){
            $_SESSION[$clave] = $valor;}
    }
    
    /*
     * Obté un valor a partir de la seva clau
     * 
     * @params String $clave
     */
    public static function get($clave)
    {
        if(isset($_SESSION[$clave])){
        return $_SESSION[$clave];}
    }
}