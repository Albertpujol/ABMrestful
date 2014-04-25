<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserController extends AbstractController{
    
    protected $gdb=null;
    
    function __construct() {
        $this->gdb=userSPDO::singleton();
    }
    
    
    public function get($request){//ho utilitzem per mostrar els usuaris
        
        $users = $this->ViewUsers();
        switch(count($request->url_elements)){
            case 1:
                return $users;
                break;
            case 2:
                $users_id = $request->url_elements[1];
                $users = $this->ViewUsers($users_id);
                return $users;
                break;
        }
    }
    
    public function post($request)//ho utilitzem per insertar un usuari nou
    {
        echo $_POST["id"];
        $consulta= "INSERT INTO usuaris (id,nom,password,email) VALUES (?,?,?,?);";
        $consulta_sql = $this->gdb->prepare($consulta);
        $consulta_sql->bindParam(1,$_POST["id"]);
        $consulta_sql->bindParam(2,$_POST["nom"]);
        $consulta_sql->bindParam(3,$_POST["password"]);
        $consulta_sql->bindParam(4,$_POST["email"]);
        $consulta_sql->execute();
        $consulta_sql->fetch(PDO::FETCH_ASSOC);
        return "usuari afegit";   
    }
    
    public function put($request)
    {
        $id_user = $request->parameters['id'];
        $nom = $request->parameters['nom'];
        $password = $request->parameters['password'];
        $email = $request->parameters['email'];
        /*$id = (int) $id_user;
        print_r($id);*/
        $consulta= "UPDATE usuaris SET nom =:nom, password =:password,email =:email WHERE id = :id";
        $consulta_sql = $this->gdb->prepare($consulta);
        $consulta_sql->bindValue(":id",$id_user);
        $consulta_sql->bindValue(":nom",$nom);
        $consulta_sql->bindValue(":password",$password);
        $consulta_sql->bindValue(":email",$email);
        $consulta_sql->execute();
        $consulta_sql->fetch(PDO::FETCH_ASSOC);
        return "usuari Actualitzat"; 
        
    }
    
    public function login($request){
        

        //$password = $request->parameters['password'];
       // $email = $request->parameters['email'];
        /*$id = (int) $id_user;
        print_r($id);*/
        try
        {
        $consulta= "SELECT id,nom,email FROM usuaris WHERE password =? AND email =?";
        $consulta_sql = $this->gdb->prepare($consulta);
        $consulta_sql->bindParam(1,$_POST['password']);
        $consulta_sql->bindParam(2,$_POST['email']);
        $consulta_sql->execute();
        $consulta_sql->fetch(PDO::FETCH_ASSOC);
        
                
        $Filas= $consulta_sql->RowCount();
       
            if($Filas == 1)
            {
 
                return true;
                return "Usuari loguejat correctament";
                
            }else{
                return false;
            }

        
          } catch (Exception $ex) {
              print "Error:".$ex->getMessage();
         }
        return "Usuari loguejat"; 
        
    }
    
    public function delete($request)//ho utilitzem per eliminar un usuari existent per id
    {
        $id_user = $request->parameters['id'];
        /*$id = (int) $id_user;
        print_r($id);*/
        $consulta= "DELETE FROM usuaris WHERE id = :id";
        $consulta_sql = $this->gdb->prepare($consulta);
        $consulta_sql->bindValue(":id",$id_user);
        $consulta_sql->execute();
        $consulta_sql->fetch(PDO::FETCH_ASSOC);
        return "usuari eliminat";   
    }
    
   
    
    
    
    protected function ViewUsers($id = NULL)//ho utilirzem per fer les sentencies per mostrar els usuaris
    {
            try{
                if(!empty($id)){
                    $con = $this->gdb->query("SELECT * FROM usuaris WHERE id =".$id);
                    $users = $con->fetchAll(PDO::FETCH_OBJ);
                }
            else{
                    $con = $this->gdb->query("SELECT * FROM usuaris");
                    $users = $con->fetchAll(PDO::FETCH_OBJ);
            }
        } 
        catch (Exception $ex) {
            echo 'ERROR: '.$ex->getMessage();
        }
        return $users;

}


          
         }
    
   