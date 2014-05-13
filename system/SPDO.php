<?php
/*
 * Per connectar-se a la base de dades
 * 
 * @author Ferran <feerraan@gmail.com>
 */
    class SPDO extends PDO
    {
            private static $instance = null;
            /*
             * Agafa del fitxer config.json els parametres necessaris per a la connexió a la base de dades
             * com el host, nom de la base de dades, usuari i contrasenya
             */
            public function __construct()
            {
                    $config = Config::getInstance();
                    try{
                        parent::__construct($config->driver.':host=' . $config->dbhost . ';dbname=' .$config->dbname,$config->dbuser, $config->dbpass);}
                    catch (PDOException $e) {
                     echo 'Connection failed: ' . $e->getMessage();}

            }
            
            /*
             *  En cas de no tenir instancia, crea una nova
             * 
             * @return self::$instance
             */
            public static function singleton()
            {
                    if( self::$instance == null )
                    {
                            self::$instance = new self();
                    }
                    return self::$instance;
            }
            
    }

