    <?php
/**
 * 
 */
    class userSPDO extends PDO{
       
        private static $instance=null;
        
        CONST dsn = "mysql:host=localhost;dbname=restful";
        CONST user = "Albert";//El usuari sera el que hagim creat al mysql amb privilegis sobre aquesta base de dades
        CONST password = "Redbull200*";
        
        function __construct() {
            try{
                parent::__construct(self::dsn,self::user,self::password);
            } catch (Exception $ex) {
                echo "Connection failed:  ".$ex->getMessage();
            }
                
        }
        
        static function singleton(){
            if(self::$instance == null)
            {
                self::$instance = new self();
            }
            return self::$instance;
        }
    }
    
