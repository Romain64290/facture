<?php


require('include/config.inc.php');
require('include/connexion.inc.php');
require('include/model.inc.php');

$captcha;
      	
      	
      	if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
        	
			
?>			
			
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transmission Dématérialisée de Factures</title>

    <!-- Bootstrap core CSS -->
    <link href="include/css/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="include/css/bootstrap-theme.min.css" rel="stylesheet">

    
    
    
<style>
.profile-header div {
display: inline-block;
vertical-align: bottom;
float: none;
margin: -2px;
	}

.btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  font-size: 100px;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  background: red;
  cursor: inherit;
  display: block;
}
input[readonly] {
  background-color: white !important;
  cursor: text !important;
}


</style>

<script src='https://www.google.com/recaptcha/api.js'></script>


</head>

<body>
<div class="container">
<img src="include/img/bgBandeauHaut.png" class="img-responsive" align="right">
		<div class="page-header">
		<div class="row profile-header">
  <div class="col-md-2"><img src="include/img/facture_04.png" class="img-responsive"></div>
  <div class="col-md-10"><p class="text-left"><h2>Transmission Dématérialisée de Factures</h2></p></div>
		</div>
		</div>
	<div class="row">
<h3><p class="bg-info">&nbsp; Attention !</p></h3>
	</div>			
			
<h4>Merci de cliquer sur la Captcha de la page précédente afin de valider l'envoi de votre formulaire.</h4><a href="#" onClick="javascript:window.history.go(-1)">Retour à la page précédente</a>
       
</div> 
 <div id="footer">
		<div  align="center"><br><FONT size="2pt">Réalisé par le service Développement et innovation des outils et pratiques numériques - 2015<br><br></FONT></div>

	</div>  
	
  </body>
</html>
<?		  
		  
		  
		exit;
		  
		  
		  
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfA0BcTAAAAAEdLj5AhxXvftUWl0vxRDb5d7o0O&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        //$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LezOhITAAAAAMHXdVI1zpYdQyrrVy4iz4g6mCYW&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        if($response.success==false)
        {
          echo '<h2>Vous êtes un spammeur !</h2>';
        }else
        {



// Insertion des elements de la facture dans la base et Affichage d'une message d"erreur en cas de pb
$resultat=envoiFactures($_POST['nom_societe'],$_POST['contact_societe'],$_POST['email_societe'],$_POST['capp2'],$_POST['villedepau2'],$_FILES['upload1']['name'],$_FILES['upload2']['name'],$_FILES['upload3']['name'],$_FILES['upload4']['name'],$_FILES['upload5']['name']);
if(!$resultat){Header("Location:erreur.html");}

else{
	
// Recuperation de l'id de l'envoi 
$id_envoi=$resultat;

//Formatage du nom_societe pour l'upload
$nom_societe=nettoyerChaine($_POST['nom_societe']);

// recupere chemin de destination des factures 
$resultat_chemin=rechercheChemin($id_envoi);
$destination_factures=CHEMIN_FACTURES.$resultat_chemin[0]->dossier_direction."/";

//upload des fichiers, test d'encryptage, envoi des fichier cryptés par email	
$televersement="ok";

if($_FILES['upload1']['size'] > 0) {$resultat_upload1=uploadFractures($_FILES['upload1'],$id_envoi,$destination_factures,$nom_societe,1);if (!$resultat_upload1){ $televersement="no";} else{testCryptage($resultat_upload1);}}
if($_FILES['upload2']['size'] > 0) {$resultat_upload2=uploadFractures($_FILES['upload2'],$id_envoi,$destination_factures,$nom_societe,2);if (!$resultat_upload2){ $televersement="no";} else{testCryptage($resultat_upload2);}}
if($_FILES['upload3']['size'] > 0) {$resultat_upload3=uploadFractures($_FILES['upload3'],$id_envoi,$destination_factures,$nom_societe,3);if (!$resultat_upload3){ $televersement="no";} else{testCryptage($resultat_upload3);}}
if($_FILES['upload4']['size'] > 0) {$resultat_upload4=uploadFractures($_FILES['upload4'],$id_envoi,$destination_factures,$nom_societe,4);if (!$resultat_upload4){ $televersement="no";} else{testCryptage($resultat_upload4);}}
if($_FILES['upload5']['size'] > 0) {$resultat_upload5=uploadFractures($_FILES['upload5'],$id_envoi,$destination_factures,$nom_societe,5);if (!$resultat_upload5){ $televersement="no";} else{testCryptage($resultat_upload5);}}

if($televersement=="no"){Header("Location:erreur.html");} 

else{
		
	envoiEmailConfirmation($_POST['email_societe']);		
		
	Header("Location:confirmation.html");}

}
}



?>