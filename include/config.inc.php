<?php
 
  /**
 *Définition de l'ensemble des constantes
 * 
 **/ 
   
   
   
  // Adresse du serveur de base de données
  define('DB_SERVEUR', 'localhost');
 
  // Login
  define('DB_LOGIN','root');
 
  // Mot de passe
  define('DB_PASSWORD','eruption');
 
  // Nom de la base de données
  define('DB_NOM','factures');
 
  // Driver correspondant à la BDD utilisée
  define('DB_DSN','mysql:host='. DB_SERVEUR .';dbname='. DB_NOM);

    
  // DATE DU JOUR (DATE TIME)
  define('DATETIME_JOUR', date('Y-m-d H:i:s'));
  
    // URL-CHEMIN_FACTURES
  define('CHEMIN_FACTURES', '/var/www/factures/factures/');
  
   // MAX-SIZE-UPLOAD
  define('MAX-SIZE-UPLOAD', '1000000000');
  
  // Réglage des locales
  setlocale(LC_ALL, 'fr_FR.UTF-8');
  
  ?>
