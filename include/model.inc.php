<?


/***********************************************************************
 * Affichage des directions d'une collectivite dans la zone de liste
 **************************************************************************/
 function afficheDirections($collectivite)
  {
    $pdo = PDO2::getInstance();
	
  try{
    	
		$select = $pdo->prepare('SELECT id_collectivite_direction,nom_direction 
		FROM collectivite_direction
		WHERE collectivite = :collectivite
		ORDER BY nom_direction ASC');
		$select->execute(array(
		':collectivite' => $collectivite
		));
		
	 
       if ($result = $select->fetchAll(PDO::FETCH_OBJ)) {
        
           $select->closeCursor();
           return $result;
        }
       return false;
    }
  catch (PDOException $e){
       echo $e->getMessage() . " <br><b>Erreur lors de l'affichage des directions d'une collectivité dans la zone de liste</b>\n";
	throw $e;
        exit;
    }
}
 
 
 /**************************************************************************
 * Insertion des facture dans la base de donnees par une societe
***************************************************************************/
 
 function envoiFactures($nom_societe,$contact_societe,$email_societe,$capp2,$villedepau2,$upload1,$upload2,$upload3,$upload4,$upload5)
  {
  	
    $pdo = PDO2::getInstance();

	// creation de la liste des factures envoyées et calcul du nombre
	$liste_factures_societe=$upload1.",".$upload2.",".$upload3.",".$upload4.",".$upload5;
	$nbr_factures_societe=0;
	if($upload1 !=""){++$nbr_factures_societe;}
	if($upload2 !=""){++$nbr_factures_societe;}
	if($upload3 !=""){++$nbr_factures_societe;}
	if($upload4 !=""){++$nbr_factures_societe;}
	if($upload5 !=""){++$nbr_factures_societe;}
	

	// Selection de la collectivite à facturer
	if($capp2 !=""){$collectivite_facturee_societe=$capp2;}else {$collectivite_facturee_societe=$villedepau2;}
	
// insertion dans la base de données
$insert = $pdo->prepare('INSERT INTO societe (nom_societe,contact_societe,email_societe,collectivite_facturee_societe,nbr_factures_societe,liste_factures_societe,etat_facture_societe,ip_societe,date_envoi_societe)
VALUES(:nom_societe,:contact_societe,:email_societe,:collectivite_facturee_societe,:nbr_factures_societe,:liste_factures_societe,:etat_facture_societe,:ip_societe,:date_envoi_societe)');
 
 	$execute=$insert->execute(array(
	'nom_societe' => $nom_societe,
	'contact_societe' => $contact_societe,
	'email_societe' => $email_societe,
	'collectivite_facturee_societe' => $collectivite_facturee_societe,
	'nbr_factures_societe' => $nbr_factures_societe,
	'liste_factures_societe' => $liste_factures_societe,
	'etat_facture_societe' => 1,
	'ip_societe' => $_SERVER['REMOTE_ADDR'],
	'date_envoi_societe' => date('Y-m-d H:i:s')
	));  
  	
  if (!$execute) {return FALSE;} 
  else{$lastId = $pdo->lastInsertId(); return $lastId;}
 				
}
 
 
 /**************************************
 * Nettoyage de chaine de carracteres
**************************************/ 
// transforme le non societe pour l'upload

function nettoyerChaine($chaine)
{
	$caracteres = array(
		'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
		'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
		'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
		'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
		'Œ' => 'oe', 'œ' => 'oe',
		'$' => 's');

	$chaine = strtr($chaine, $caracteres);
	$chaine = preg_replace('#[^A-Za-z0-9]+#', '-', $chaine);
	$chaine = trim($chaine, '-');
	$chaine = strtolower($chaine);

	return $chaine;
}
  
 
 /**************************************************************************
 * Recherche chemin de destination d'une facture
***************************************************************************/
 
 function rechercheChemin($id_envoi)
 {
  $pdo = PDO2::getInstance();
	
  try{
    	
		$select = $pdo->prepare('SELECT dossier_direction FROM collectivite_direction
		INNER JOIN societe ON collectivite_direction.id_collectivite_direction=societe.collectivite_facturee_societe 
		WHERE id_societe = :id_societe');
		$select->execute(array(
		':id_societe' => $id_envoi
		));
		
       if ($result = $select->fetchAll(PDO::FETCH_OBJ)) {
        
           $select->closeCursor();
           return $result;
        }
       return false;
    }
  catch (PDOException $e){
       echo $e->getMessage() . " <br><b>Erreur lors de la recherche chemin de destination d'une facture</b>\n";
	throw $e;
        exit;
    }
}
 
 
 
  /********************************
 * Upload des factures
*********************************/  
   
 function uploadFractures($file,$id_envoi,$destination_factures,$nom_societe,$num_facture)
{			
// Creer le repertoire s'il n'existe pas
//mkdir($destination_factures, 0777, true);

$maxsize=1201452111452;

	
 $destination=$destination_factures.$nom_societe."_".$id_envoi."_".$num_facture.".pdf";  
     
    if ( $file['error'] > 0) return FALSE;

  			if ($file['size'] < $maxsize) {
  
     
		move_uploaded_file($file['tmp_name'], $destination);
                chmod($destination, 0664);}
 
return $destination;


}	

 /*******************************************************
 * Test si facture cryptée et envoi par email
********************************************************/  
// test et move facture dans le dossier Encrypt
// recuperer le non du fichier uploader

function testCryptage($file2)
{
$chercher = "/Encrypt";
$filename = file($file2);// Lit le fichier et renvoie le résultat dans un tableau
$fin = false;

foreach($filename as $ligne){
	if(strstr($ligne,$chercher)){ 
		$fin = true;
		break;
	}
}
 
if($fin === true){
	// Deplace la facture dans le dossier Encrypt
	rename($file2,CHEMIN_FACTURES."encrypt/".basename($file2));
	//echo $file2; echo"<br>";
	//echo CHEMIN_FACTURES."encrypt/".basename($file2);
	$chemin_facture_cryptee=CHEMIN_FACTURES."encrypt/".basename($file2);
	$facture_cryptee=basename($file2);
	
	
	// envoi du fichier par email
	envoiEmailFactureCryptee ($chemin_facture_cryptee,$facture_cryptee);

}

}


 /*******************************************************
 * Envoi email societe de confirmation upload
********************************************************/ 

function envoiEmailConfirmation($email_societe,$upload1,$upload2,$upload3,$upload4,$upload5)

{
	
// Envoi des emails

// Création d'un nouvel objet $mail
$mail = new PHPMailer();
// Encodage
$mail->CharSet = 'UTF-8';

// creation de la liste des factures envoyées 
$liste_factures_societe=$upload1."  ".$upload2."  ".$upload3."  ".$upload4."  ".$upload5;


//=====Corps du message
$body = "<html><head></head>
<body>
Bonjour,<br>
<br>
Nous vous remercions du dépôt de votre (vos) facture(s) sur notre site.  Elles seront prises en compte dans les meilleurs délais.<br>
Les factures déposées sont : ".$liste_factures_societe."<br>
<br>
Salutations<br>
<br>
</body>
</html>";
//==========


// Expediteur, adresse de retour et destinataire :
$mail->SetFrom("NO-REPLY@agglo-pau.fr", "Agglomération Pau-Pyrénées"); //L'expediteur du mail
$mail->AddReplyTo("NO-REPLY@agglo-pau.fr", "NO REPLY"); //Pour que l'usager réponde au mail

// Si on a le nom $mail->AddAddress("romain_taldu@hotmail.com", "Romain perso"); 
 //mail du destinataire
$mail->AddAddress($email_societe); 


// Sujet du mail
$mail->Subject = "Agglomération Pau-Pyrénées - Accusé de dépôt de facture";
// Le message
$mail->MsgHTML($body);

//Attach a file
//$mail->addAttachment('colas-pau_158_2.pdf');
//$mail->addAttachment('elcom-reso_147_1.pdf');

// Envoi de l'email
$mail->Send();

unset($mail);


	
}



 /*******************************************************
 * Envoi email facture cryptee
********************************************************/ 

function envoiEmailFactureCryptee ($chemin_facture_cryptee,$facture_cryptee)

{

// Création d'un nouvel objet $mail
$mail = new PHPMailer();
// Encodage
$mail->CharSet = 'UTF-8';
	
//Email avec piece jointe
//$mail = 'r.taldu@agglo-pau.fr;s.garcia@agglo-pau.fr;s.leclercq@agglo-pau.fr'; // Déclaration de l'adresse de destination.



//=====Corps du message
$body = "<html><head></head>
<body>
Bonjour,<br>
<br>
Ci-joint, une facture cryptée<br>
<br>
Cordialement,<br>
<br>
</body>
</html>";
//==========

 // Expediteur, adresse de retour et destinataire :
$mail->SetFrom("NO-REPLY@agglo-pau.fr", "Agglomération Pau-Pyrénées"); //L'expediteur du mail
$mail->AddReplyTo("NO-REPLY@agglo-pau.fr", "NO REPLY"); //Pour que l'usager réponde au mail

// Si on a le nom $mail->AddAddress("romain_taldu@hotmail.com", "Romain perso"); 
 //mail du destinataire
$mail->AddAddress("r.taldu@agglo-pau.fr");
$mail->AddAddress("s.garcia@agglo-pau.fr"); 
$mail->AddAddress("s.leclercq@agglo-pau.fr"); 


// Sujet du mail
$mail->Subject = "[Factures dématérialisées] Facture protégée";
// Le message
$mail->MsgHTML($body);

//Attach a file
$mail->addAttachment($chemin_facture_cryptee);
//$mail->addAttachment('$chemin_facture_cryptee');

// Envoi de l'email
$mail->Send();

unset($mail);

}



 ?> 
