<?php
/**
 *  Desenvolupa totes les funcions de tipus Ajax
 *
 * @author Ferran <feerraan@gmail.com>
 */
class AjaxModel extends Model{
    
    public function __construct($arr) {
        parent::__construct($arr);
    }
        
    //HOTELS
    /**
    * Per carregar les ciutats
    * 
    * Per carregar les ciutats dels hotels de la base de dades a les options del select
    *
    * @return $res
    */
    public function ciutats() {
        $sql ="SELECT DISTINCT ciutat FROM hotels ORDER BY ciutat ASC";
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
   
        return $res;
    }
    
    /**
    * Per mostrar els hotels filtrats de la base de dades
    *
    * @return $res
    */
    public function buscar_hotels() {
        $ciutat = $_POST['ci'];
        $categoria = $_POST['ca'];
        $places = $_POST['pl'];
        $preu_min = $_POST['pmi'];
        $preu_max = $_POST['pma'];
        
        $usuari = session::get('idusuari');
        
        $sql = "SELECT DISTINCT hotels.nom AS hotelnom, hotels.ciutat AS hotelciutat, hotels.categoria AS hotelcategoria,
                        serveis.id AS servei_id, reserves.idusuari AS reservesidusuari, serveis.preu AS serveipreu, serveis_reservats.idreserva AS idreserva,
                        reserves.status AS status, hotels.latitud AS latitud, hotels.longitud AS longitud

            FROM hotels 
            LEFT JOIN serveis ON hotels.id = serveis.id
            LEFT JOIN serveis_reservats ON serveis.id = serveis_reservats.idservei
            LEFT JOIN reserves ON serveis_reservats.idreserva = reserves.id

            WHERE hotels.ciutat = '".utf8_decode($ciutat)."' AND serveis.nplaces >= ".$places." AND serveis.preu >= ".$preu_min." 
                AND serveis.preu <= ".$preu_max."";
        if($categoria != 0)
            $sql = $sql . " AND hotels.categoria = ".$categoria." ";
        
        $sql = $sql . " GROUP BY hotelnom;";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        
        return $res;
    }
    
    //VOLS
    /**
    * Per carregar les estacions d'origen
    * 
    * Per carregar les estacions d'origen de la base de dades a les options del select
    *
    * @return $res
    */
    public function origen() {
        $sql ="SELECT DISTINCT aeroport FROM vols ORDER BY aeroport ASC";
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
   
        return $res;
    }
    
    /**
    * Per carregar les estacions destí
    * 
    * Per carregar les estacions destí de la base de dades a les options del select, a raó de l'estació d'origen seleccionada
    *
    * @return $res
    */
    public function desti() {
        $origen = $_POST['o'];
        print $origen;
        $sql ="SELECT DISTINCT dest FROM vols WHERE aeroport = '".$origen."' ORDER BY dest ASC";
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
   
        return $res;
    }
    
    /**
    * Per mostrar els vols filtrats de la base de dades
    */
    public function buscar_vols() {
        $origen = $_POST['o'];
        $desti = $_POST['d'];
        $places = $_POST['pl'];
        $preu_min = $_POST['pmi'];
        $preu_max = $_POST['pma'];
        
        $sql = "SELECT DISTINCT vols.dest AS desti, vols.aeroport AS origen, serveis.id AS servei_id,
            reserves.idusuari AS reservesidusuari, serveis.preu AS serveipreu, serveis_reservats.idreserva AS idreserva,
            reserves.status AS status 
            
            FROM vols 
            LEFT JOIN serveis ON vols.id = serveis.id
            LEFT JOIN serveis_reservats ON serveis.id = serveis_reservats.idservei
            LEFT JOIN reserves ON serveis_reservats.idreserva = reserves.id
            
            WHERE vols.aeroport = '".$origen."' AND vols.dest = '".$desti."' AND serveis.nplaces >= ".$places."
                AND serveis.preu >= ".$preu_min." AND serveis.preu <= ".$preu_max.";";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        
        return $res;
    }
    
    //PLANS
    /**
    * Per carregar els plans
    * 
    * Per carregar els plans de la base de dades a les options del select, a raó de l'estació d'origen seleccionada
    */
    public function pla() {
        $sql ="SELECT DISTINCT descrip FROM plans ORDER BY descrip ASC";
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
   
        return $res;
    }
    
    /**
    * Per mostrar els plans filtrats de la base de dades
    */
    public function buscar_plans() {
        $pla = $_POST['p'];
        $places = $_POST['pl'];
        $preu_min = $_POST['pmi'];
        $preu_max = $_POST['pma'];
        
        $sql = "SELECT DISTINCT plans.descrip AS descripcio, serveis.id AS servei_id,
            reserves.idusuari AS reservesidusuari, serveis.preu AS serveipreu, 
            serveis_reservats.idreserva AS idreserva, reserves.status AS status 
            FROM plans 
            LEFT JOIN serveis ON plans.id = serveis.id
            LEFT JOIN serveis_reservats ON serveis.id = serveis_reservats.idservei
            LEFT JOIN reserves ON serveis_reservats.idreserva = reserves.id
            
            WHERE plans.descrip = '".utf8_decode($pla)."' AND serveis.nplaces >= ".$places." AND serveis.preu >= ".$preu_min." 
                AND serveis.preu <= ".$preu_max.";";
                
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        
        return $res;
    }
    
    /**
    * Ens permet contractar qualsevol dels hotels, plans o vols
    */
    public function contractar() {
        $id_servei = $_POST['id_serv'];
        $places = $_POST['pl'];
        $usuari = session::get('idusuari');
        
        $sql = "CALL sp_afegir_a_reserva (".$usuari.", ".$id_servei.", ".$places.");";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        
        return $res;
    }
    
    /**
    * Mostra una taula per tal que l'usuari pugui veure la reserva que té oberta actualment
    */
    public function mostrar_reserves_obertes(){
        $usuari = session::get('idusuari');
        $sql = "SELECT serveis.id AS serveisid, hotels.nom AS hotelsnom, vols.dest AS volsdesti, 
            vols.aeroport AS volsorigen, plans.descrip AS plansdescrip, serveis_reservats.places AS srplaces, 
            serveis_reservats.preu_servei AS srpreuservei, reserves.preu AS reservespreu, hotels.id AS hotelsid,
            vols.id AS volsid, plans.id AS plansid
            
            FROM reserves 
                INNER JOIN serveis_reservats ON reserves.id = serveis_reservats.idreserva 
                INNER JOIN serveis ON serveis.id = serveis_reservats.idservei
                LEFT JOIN hotels ON hotels.id = serveis.id
                LEFT JOIN vols ON vols.id = serveis.id
                LEFT JOIN plans ON plans.id = serveis.id
                
                WHERE reserves.idusuari = ".$usuari." AND reserves.status = 'Oberta';";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        if($query->rowCount()>=1) {
            $res=$query->fetchAll();
            return $res;
        }
        else
            return FALSE;
    }
    
    /**
    * Mostra una taula per tal que l'usuari pugui veure les reserves ja pagades
    */
    public function mostrar_reserves_tancades(){
        $usuari = session::get('idusuari');
        $sql = "SELECT DISTINCT reserves.id, tipus_pagament.tipus AS metodepaga, pagaments.pagament_data AS datapaga, reserves.preu AS import FROM tipus_pagament
                INNER JOIN pagaments ON tipus_pagament.id = pagaments.tipus
                INNER JOIN reserves ON pagaments.idreserva = reserves.id
                INNER JOIN serveis_reservats ON reserves.id = serveis_reservats.idreserva 
                INNER JOIN serveis ON serveis.id = serveis_reservats.idservei 
                WHERE reserves.idusuari = ".$usuari." AND reserves.status = 'Pagada'
                ORDER BY datapaga;";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        if($query->rowCount()>=1) {
            $res=$query->fetchAll();
            return $res;
        }
        else
            return FALSE;
    }
    
    /**
    * Per eliminar un servei de la reserva que tingui oberta
    * 
    */
    public function eliminar_servei(){
        $usuari = session::get('idusuari');
        $servei = $_POST['id_serv'];
        
        $sql = "CALL eliminar_reserva(".$usuari.", ".$servei.");";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        
        return true;
    }
    
    /**
    * Per cancelar una reserva oberta
    */
    public function cancelar_reserva(){
        $usuari = session::get('idusuari');
        
        $sql = "CALL sp_cancelar_reserva(".$usuari.");";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        
        return true;
    }
    
    /**
    * Per pagar la reserva oberta
    */
    public function pagar_reserva(){
        $usuari = session::get('idusuari');
        $metode = $_POST['me'];
        
        $sql = "CALL pagar_reserva(".$usuari.", ".$metode.");";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        
        return true;
    }
    
    /**
    * Realitzar funcions d'administració
    * 
    * Realitzar funcions d'administració, en cas de ser un usuari amb el rol d'administrador
    */
    public function mostrar_administracio(){
        $usuari = session::get('idusuari');
        $sql = "SELECT * FROM usuaris WHERE idrol = 1 AND id = ".$usuari.";";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        return $res;
    }
    
    /**
    * Mostra els usuaris registrats a la base de dades
    * 
    * En cas de ser un usuari amb el rol d'administrador, mostra els usuaris registrats a la base de dades
    */
    public function mostrar_usuaris(){
        $sql = "SELECT usuaris.id AS id, usuaris.nom AS nom, usuaris.cognoms AS cognoms, usuaris.email AS email,
            usuaris.idrol AS idrol, rols.descrip AS descrip FROM usuaris INNER JOIN rols ON usuaris.idrol = rols.id";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        $res=$query->fetchAll();
        return $res;
    }
    
    /*public function eliminar_usuari(){
        $usuari = $_POST['id'];
        
        $sql = "DELETE FROM usuaris WHERE id = ".$usuari." ;";
        
        $query=$this->db->prepare($sql);
        $query->execute();
        
        return true;
    }*/  
    
}
