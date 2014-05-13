<?php
/**
 *  Desenvolupa totes les funcions de tipus Ajax
 *
 * @author Ferran <feerraan@gmail.com>
 */


class AjaxController extends ControllerBase{
    protected $model;
    protected $view;
    //private $conf;
    /**
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
       //carregar la configuració
       $this->model= new AjaxModel($arr);
    }
    
    public function index(){}
    
    //HOTELS
    /**
    * Per carregar les ciutats
    * 
    * Per carregar les ciutats dels hotels de la base de dades a les options del select
    */
    public function ciutats() {        
        $results = $this->model->ciutats();
        echo "<option id='0'>Selecciona ciutat...</option>";
        foreach($results as $clave=>$valor) {
            echo "<option>".utf8_encode($valor['ciutat'])."</option>";
        }
    }
    
    /**
    * Per mostrar els hotels filtrats de la base de dades
    */
    public function buscar_hotels() {
        $results = $this->model->buscar_hotels();
        
        foreach ($results as $clave => $valor) {    
            echo "<div class='opcions' id='opc_".$valor['servei_id']."'>
                    <h3>".$valor['hotelnom']."</h3>
                    <ul><li>".utf8_encode($valor['hotelciutat'])."<img id='ubicacio' onClick='mostrar_mapa(".$valor['latitud'].",".$valor['longitud'].");' width='15' src='".APP_W."/application/public/themes/".THEME."/img/ubicacio.png' /></li>
                    <li>".$valor['hotelcategoria']." Estrelles</li>
                    <li>".$valor['serveipreu']." € per persona</li>
                    </ul>
                    <h5>Import total: ".$valor['serveipreu']*$_POST['pl']." €</h5>";
            
                    if(session::get('islogged') == TRUE)
                        echo "<input type='button' value='Contractar' onClick='contractar(".$valor['servei_id'].");'/>";
                    
            echo "</div>";
        }
    }
    
    //VOLS
    /**
    * Per carregar les estacions d'origen
    * 
    * Per carregar les estacions d'origen de la base de dades a les options del select
    */
    public function origen() {        
        $results = $this->model->origen();
        echo "<option id='0'>Selecciona aeroport d'origen...</option>";
        foreach($results as $clave=>$valor) {
            echo "<option>".utf8_encode($valor['aeroport'])."</option>";
        }
    }
    
    /**
    * Per carregar les estacions destí
    * 
    * Per carregar les estacions destí de la base de dades a les options del select, a raó de l'estació d'origen seleccionada
    */
    public function desti() {        
        $results = $this->model->desti();
        echo "<option id='0'>Selecciona destí...</option>";
        foreach($results as $clave=>$valor) {
            echo "<option>".utf8_encode($valor['dest'])."</option>";
        }
    }
    
    /**
    * Per mostrar els vols filtrats de la base de dades
    */
    public function buscar_vols() {
        $results = $this->model->buscar_vols();
        
        foreach ($results as $clave => $valor) {
            echo "<div class='opcions' id='opc_".$valor['servei_id']."'>
                    <h3>".utf8_encode($valor['origen'])." - ".utf8_encode($valor['desti'])."</h3>
                    <ul>
                        <li>".$valor['serveipreu']." € per persona</li>
                    </ul>
                    <h5>Import total: ".$valor['serveipreu']*$_POST['pl']." €</h5>";
                    
                    if(session::get('islogged') == TRUE)
                        echo "<input type='button' value='Contractar' onClick='contractar(".$valor['servei_id'].");'/>";
                    
            echo "</div>";
        }
	
    }
    
    //PLANS
    /**
    * Per carregar els plans
    * 
    * Per carregar els plans de la base de dades a les options del select, a raó de l'estació d'origen seleccionada
    */
    public function pla() {        
        $results = $this->model->pla();
        echo "<option id='0'>Selecciona pla...</option>";
        foreach($results as $clave=>$valor) {
            echo "<option>".utf8_encode($valor['descrip'])."</option>";
        }
    }
    
    /**
    * Per mostrar els plans filtrats de la base de dades
    */
    public function buscar_plans() {
        $results = $this->model->buscar_plans();
        
        foreach ($results as $clave => $valor) {
            echo "<div class='opcions' id='opc_".$valor['servei_id']."'>
                    <h3>".$valor['descripcio']."</h3>
                    <ul>
                    <li>".$valor['serveipreu']." € per persona</li>
                    </ul>
                    <h5>Import total: ".$valor['serveipreu']*$_POST['pl']." €</h5>";
                    
                    if(session::get('islogged') == TRUE)
                        echo "<input type='button' value='Contractar' onClick='contractar(".$valor['servei_id'].");'/>";
                    
            echo "</div>";
        }
	
    }
    
    /**
    * Ens permet contractar qualsevol dels hotels, plans o vols
    */
    public function contractar() {
        $results = $this->model->contractar();
        return true;
    }
    
    /**
    * Mostra una taula per tal que l'usuari pugui veure la reserva que té oberta actualment
    */
    public function mostrar_reserves_obertes(){
        $results = $this->model->mostrar_reserves_obertes();
        if ( $results != FALSE ) {
        echo "<table cellspacing='10' cellpadding='10'>
                <tr>
                <th>Servei</th>
                <th>Nom del servei</th>
                <th>Places reservades</th>
                <th>Preu</th>
            </tr>";
        foreach ($results as $clave => $valor) {
            echo "<tr>";
                    if( $valor['serveisid'] == $valor['hotelsid'])
                        echo "<td><i>Hotel</i></td><td>".$valor['hotelsnom'];
                    elseif( $valor['serveisid'] == $valor['volsid'])
                        echo "<td><i>Vol</i></td><td>".$valor['volsorigen'].' - '.$valor['volsdesti'];
                    elseif( $valor['serveisid'] == $valor['plansid'])
                        echo "<td><i>Pla</i></td><td>".$valor['plansdescrip'];
                    echo "</td>
                    <td>".$valor['srplaces']."</td>
                    <td>".$valor['srpreuservei']." €</td>
                    <td><a class='eliminar' onClick='elimina(".$valor['serveisid'].")';>[eliminar]</a></td>
                </tr>";
        }
        echo "  <tr class='noborder'>
                <td><input type='button' value='Pagar reserva actual' onClick='mostrar_pagar();'/></td>
                <th>IMPORT TOTAL</th>
                <td><span>".$valor['reservespreu']."  €</span></td>
                <td><a onClick='cancela();'>Cancelar</a></td>
                </tr>
            </table>";
        }
        else 
            echo "<span>No hi ha cap reserva pendent</span>";
    }
    
    /**
    * Mostra una taula per tal que l'usuari pugui veure les reserves ja pagades
    */
    public function mostrar_reserves_tancades(){
        $results = $this->model->mostrar_reserves_tancades();
        if ( $results != FALSE ) {
        echo "<table cellspacing='10' cellpadding='10'>
                <tr>
                <th>Data de pagament</th>
                <th>Pagament</th>
                <th>Import total</th>
            </tr>";
        foreach ($results as $clave => $valor) {
            echo "<tr>
                    <td>".$valor['datapaga']."</td>
                    <td>".$valor['metodepaga']."</td>
                    <td>".$valor['import']." €</td>
                </tr>";
        }
        echo "</table>";
        }
        else 
            echo "<span>Encara no s'ha realitzat cap reserva</span>";
    }
    
    /**
    * Per eliminar un servei de la reserva que tingui oberta
    * 
    * Per eliminar un servei de la reserva que tingui oberta, cridant a la funcio mostrar_reserves_obertes(), per tal de refrescar la reserva actual sense el servei eliminat
    */
    public function eliminar_servei(){
        $results = $this->model->eliminar_servei();
        if($results == TRUE)
            $this->mostrar_reserves_obertes();
    }
    
    /**
    * Per eliminar un servei de la reserva que tingui oberta
    */
    public function eliminar_servei2(){
        $this->model->eliminar_servei();
    }
    
    /**
    * Per cancelar una reserva oberta
    */
    public function cancelar_reserva(){
        $results = $this->model->cancelar_reserva();
        if($results == TRUE)
            $this->mostrar_reserves_obertes();
    }
    
    /**
    * Per pagar la reserva oberta
    */
    public function pagar_reserva(){
        $results = $this->model->pagar_reserva();
        if($results == TRUE) {
            $this->mostrar_reserves_tancades();
        }
    }
    
    /**
    * Realitzar funcions d'administració
    * 
    * Realitzar funcions d'administració, en cas de ser un usuari amb el rol d'administrador
    */
    public function mostrar_administracio(){
        $results = $this->model->mostrar_administracio();
        
        if ( $results != FALSE) {
        echo "<div id='admin'>
                <h3>Panell d'administració</h3><div id='a_f' class='fletxa derecha'></div>
            </div>
            <div id='m_admin'>
                <h4 id='usuaris'><a href='#usuaris' onClick='mostrar_usuaris();'>Usuaris</a></h4>
                <table id='m_usuaris' cellspacing='10' cellpadding='10'>
                </table>
            </div>
            <script>
                $('#admin').click(function(){
                   if ( ($('#m_admin').css('display')) == 'none' ) {
                        $('#m_admin').slideDown();
                        $('#admin #a_f').removeClass('derecha');
                        $('#admin #a_f').addClass('abajo');
                   }
                   else {
                        $('#m_admin').slideUp();
                        $('#admin #a_f').removeClass('abajo');
                        $('#admin #a_f').addClass('derecha');
                   }
                });
            </script>";
        }
        else
            echo "";
    }
    
    /**
    * Mostra els usuaris registrats a la base de dades
    * 
    * En cas de ser un usuari amb el rol d'administrador, mostra els usuaris registrats a la base de dades
    */
    public function mostrar_usuaris(){
        $results = $this->model->mostrar_usuaris();
        echo "<tr>
                    <th>Nom</th>
                    <th>Cognoms</th>
                    <th>Correu electrónic</th>
                    <th>Rol</th>
                </tr>";
        foreach ($results as $clave => $valor) {
            echo "<tr id=".$valor['id'].">
                    <td>".$valor['nom']."</td>
                    <td>".$valor['cognoms']."</td>
                    <td>".$valor['email']."</td>
                    <td>".$valor['descrip']."</td>
                </tr>";
        }
    }
    
    /*public function eliminar_usuari(){
        $results = $this->model->eliminar_usuari();
        if($results == TRUE)
            $this->mostrar_usuaris();
    }*/
} 
