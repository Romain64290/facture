<?php

class PDO2 extends PDO {

        private static $_instance;

        /* Constructeur : héritage public obligatoire par héritage de PDO */
        public function __construct( ) {
       
        }
        // End of PDO2::__construct() */

        /* Singleton */
        public static function getInstance() {
       
                if (!isset(self::$_instance)) {
                       
                        try {
                       
                                self::$_instance = new PDO(DB_DSN, DB_LOGIN, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
								
                       
                        } catch (PDOException $e) {
                       
                                echo $e;
                        }
                }
                return self::$_instance;
        }
        // End of PDO2::getInstance() */
}

?>