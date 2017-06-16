<?php
require('include/config.inc.php');
require('include/connexion.inc.php');
require('include/model.inc.php');
  
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
<h3><p class="bg-info">&nbsp; Vos informations</p></h3>
	</div>
	
	<form name="formulaire" role="form" class="form-horizontal" data-toggle="validator" action="upload.php" method="post" enctype="multipart/form-data">
  <div class="form-group">

    <label for="nom_societe" class="col-sm-3 control-label">Nom de la société</label>
    <div class="col-sm-5">
	<input type="text" class="form-control" id="nom_societe" name="nom_societe" placeholder="Nom Société" required>
	  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	  <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="form-group">
    <label for="contact_societe" class="col-sm-3 control-label">Contact</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="contact_societe" name="contact_societe" placeholder="Nom Contact" required>
	  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	  <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="form-group">
    <label for="email_societe" class="col-sm-3 control-label">Email</label>
    <div class="col-sm-5">
      <input type="email" class="form-control" id="email_societe" name="email_societe" placeholder="Adresse email du contact" required>
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	<div class="help-block with-errors"></div> 
 </div>
  </div>
    <h3><p class="bg-info">&nbsp; Collectivité et service  à facturer</p></h3>
      <div class="form-group">
    <label class="col-sm-3 control-label">Collectivité et service  à facturer</label>
	<div class="col-sm-5">
  <!-- Nav tabs -->
  
  
  
  <div class="form-group">
  	
  
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#capp" aria-controls="capp" role="tab" data-toggle="tab" onfocus="this.blur()" onclick="this.blur()">CAPP</a></li>
    <li role="presentation"><a href="#villedepau" aria-controls="profile" role="villedepau" data-toggle="tab" onfocus="this.blur()" onclick="this.blur()">Ville de Pau</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="capp" name="capp">
<select class="form-control" size="8" onchange="change_villedepau()" name="capp2" id="capp2">
  <optgroup label="Communauté d'Agglomération de Pau">

<?php
	
$resultat=afficheDirections(1);
  
foreach ($resultat as $key) {
	
$id_collectivite_direction = $key->id_collectivite_direction;
$nom_direction=htmlspecialchars($key->nom_direction);
	
echo "<option value=\"$id_collectivite_direction\">$nom_direction</option>";
}
?>

</select>
</div>
    <div role="tabpanel" class="tab-pane fade" id="villedepau" name="villedepau">
<select class="form-control" size="8" onchange="change_capp()" name="villedepau2" id="villedepau2">
  <optgroup label="Ville de Pau">

<?php
	
$resultat=afficheDirections(2);
  
foreach ($resultat as $key) {
	
$id_collectivite_direction = $key->id_collectivite_direction;
$nom_direction=htmlspecialchars($key->nom_direction);
	
echo "<option value=\"$id_collectivite_direction\">$nom_direction</option>";
}
?>

 </select>
	</div>
	
	   </div>
	   	   
	   <div class="alert alert-block alert-danger" id="collectivite_div" style="display:none">
      <strong>Erreur !</strong>  Vous devez choisir une collectivité et un service ! 
       </div>
	   
</div>

</div></div>

  <h3><p class="bg-info">&nbsp; Les documents à transmettre</p></h3>
	    <div class="form-group">
    <label class="col-sm-3 control-label">Envoi de (des) facture(s)</label>
<div class="col-sm-5">
                <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary btn-file">Facture 1 &hellip; <input type="file" name="upload1" id="upload1" accept="application/pdf">
                  </span>
                </span>
                 <input type="text" class="form-control" readonly>
            </div>
             <br>
             <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary btn-file">Facture 2 &hellip; <input type="file" name="upload2" id="upload2" accept="application/pdf">
                  </span>
                </span>
                 <input type="text" class="form-control" readonly>
            </div>
             <br>
             <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary btn-file">Facture 3 &hellip; <input type="file" name="upload3" id="upload3" accept="application/pdf">
                  </span>
                </span>
                 <input type="text" class="form-control" readonly>
            </div>
             <br>
             <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary btn-file">Facture 4 &hellip; <input type="file" name="upload4" id="upload4" accept="application/pdf">
                  </span> 
                </span>
                 <input type="text" class="form-control" readonly>
            </div>
            <br>
             <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary btn-file">Facture 5 &hellip; <input type="file" name="upload5" id="upload5">
                  </span>
                </span>
                 <input type="text" class="form-control" readonly>
            </div>
            <div  class="alert alert-block alert-danger" id="upload_div"  style="display:none">
     		 <strong>Erreur !</strong>  Vous devez choisir un fichier ! 
      		 </div>
            
            <span class="help-block">
                Vous pouvez transmettre plusieurs fichiers, mais dans le même service selectionné ci-dessus. Seul le format "PDF" est accepté.
            </span>
                        
                   <div class="col-sm-5">
<!-- <br><div class="g-recaptcha" data-sitekey="6LezOhITAAAAAPpy6UW_MYTfRVW4HD-3a83_tOZI" ></div><br>    <!--  Captcha agglo--pau.net -->
<br><div class="g-recaptcha" data-sitekey="6LfA0BcTAAAAAFHz-Ie7zcsaOk2EohaTbnpRsciR" ></div><br> <!-- Captcha agglo--pau.fr -->

</div>
        </div>
        
     
    </div>
</div>


</div>
<br>
<div class="container">
	<div class="row">
	    <div class="col-sm-3"></div>
		    <div class="col-sm-5">
      <div class="button" align="center">
      <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Envoyer</button>
    </div>
	</div>
</div> <!-- /container -->
<br>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="include/js/bootstrap.min.js"></script>
	<script src="include/js/validator.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="include/js/ie10-viewport-bug-workaround.js"></script>
    
    
 <script type="text/javascript">
 
function change_capp()
{  document.getElementById('capp2').selectedIndex = -1;  }


function change_villedepau()
{  document.getElementById('villedepau2').selectedIndex = -1;  }




$(function(){

    $("form").on("submit", function() {

$erreur="no";

      if($('#capp2').val()<1 && $('#villedepau2').val()<1) {
      	
		
        $("div.form-group").addClass("has-error");

        $("#collectivite_div").show("slow").delay(4000).hide("slow");
        
         $erreur="ok";

     														 }
      
 		if($('#upload1').val()<1 && $('#upload2').val()<1 && $('#upload3').val()<1 && $('#upload4').val()<1 && $('#upload5').val()<1) {
 		$( "#upload_div" ).show("slow").delay(4000).hide("slow");
 		$erreur="ok";
 		}
 		
if($erreur=="ok"){return false;}
   										 });

 					 });
  



$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });
});







</SCRIPT>
    
  <div id="footer">
		<div  align="center"><br><FONT size="2pt">Réalisé par le service Développement et innovation des outils et pratiques numériques - 2015<br><br></FONT></div>

	</div>  
	
  </body>
</html>

