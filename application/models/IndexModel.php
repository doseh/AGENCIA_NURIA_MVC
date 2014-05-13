<?php
/**
 * Model per a index
 *
 * Model per a index, realitza de certes funcions sobre el registre i la sessió de l'usuari
 * 
 * @author Ferran <feerraan@gmail.com>
 */
class IndexModel extends Model{
    /**
    * rep con a paràmetre un array associatiu que 
    * permet passar els paràmetres de la URI
    * @param array $arr
    */
    public function __construct($arr) {
        parent::__construct($arr);
    }
    
    /**
     * Permet a l'usuari iniciar sessió 
     * 
     * @param String $email, String $password
     * 
     * @return TRUE, FALSE
    */
    function login($email,$password){
        try{
            $sql="SELECT * FROM usuaris WHERE email=? AND password=?";
            $query=$this->db->prepare($sql);
            $query->bindParam(1,$email);
            $query->bindParam(2,$password);
            $query->execute();
            $res=$query->fetch();
            if($query->rowCount()==1){
                Session::set('islogged',TRUE);
                Session::set('email',$email);
                $user= serialize(new usuari($res['nom'],$res['cognoms'],$res['email'],$res['idrol']));
                Session::set('user', $res['nom']);
                Session::set('idusuari', $res['id']);
                //Session::set($user);

                return TRUE;
            }
            else {
                Session::set('islogged',FALSE);
                return FALSE;}
        }catch(PDOException $e){
            echo "Error:".$e->getMessage();
        }
    }
    
    /**
     * Permet a l'usuari tancar sessió 
     * 
     */
    function logout() {
        Session::destroy();
    }
    
    /**
     * Permet a l'usuari registrar-se a la web
     * 
     * @param String $nom, String $cognoms, String $email, String $password
    */
    function registre($nom, $cognoms, $email, $password) {
        $sql="SELECT * FROM usuaris WHERE email=?";
        $query=$this->db->prepare($sql);
        $query->bindParam(1,$email);
            
        $query->execute();
        
        if( $query->rowCount()==0 ) {
            $sql2 = 'CALL sp_nou_usuari("'.$nom.'","'.$cognoms.'","'.$email.'","'.$password.'", 2);';
            //$sql2 = 'INSERT INTO usuaris(nom,cognoms,email,password,idrol) VALUES ("'.$nom.'","'.$cognoms.'","'.$email.'","'.$password.'", 2);';
            $query=$this->db->prepare($sql2);
            $query->execute();
            
            $this->login($email, $password);
        }
        else
        {
            echo "<br /><script>alert('Usuari ja existent');</script>";
        }
        
    }
}
