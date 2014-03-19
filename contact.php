<?php
$titre_page="Contact";
$destinataire =  "";
	$message_envoye = "L'envoi du mail a r&eacute;ussi. Nous vous recontacterons d&eacute;s que possible.";
	$message_non_envoye = "L'envoi du mail a &eacute;chou&eacute;, désolé ! Vous pouvez nous envoyer un E-mail à l'adresse suivante :  ".$destinataire;
	$message_erreur_formulaire = "Vous devez d'abord <a href=\"contact.php\">envoyer le formulaire</a>.";
	$message_formulaire_invalide = "V&eacute;rifiez que tous les champs soient bien remplis et que l'E-mail soit correct.";
include("includes/db_connect.php");
include("includes/functions.php");
include("includes/class.verif.php");
$titre_page=TitrePage($bdd,"","Contact");
include("includes/head.php");
include("includes/menu.php");
?>
<div class="gris_clair">
     <div class="row-fluid corps">
         <div class="span12">
               <form id="contactForm" action="#" method="post" class="form-horizontal">
               <?php
						$nom="";
						$email="";
						$message="";
					if(isset($_POST["envoi"]))
					{
						function Rec($text)
						{
							$text = trim($text); 
							if (1 === get_magic_quotes_gpc())
							{
								$stripslashes = create_function('$txt', 'return stripslashes($txt);');
							}
							else
							{
								$stripslashes = create_function('$txt', 'return $txt;');
							}
				
				
							$text = $stripslashes($text);
							$text = htmlspecialchars($text, ENT_QUOTES);
							$text = nl2br($text);
							return $text;
						}
				
						
				
						$nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
						$email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
						$message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
						$verifmail=Verif($email,"Adresse E-mail",10,64,"email");
				
				        if($verifmail==1)
						{
						    if (($nom != '') && ($email != '') && ($message != ''))
						    {
				
							    $headers = 'From: '.$nom.' <'.$email.'>' . "\r\n";
							    $message = str_replace("&#039;","'",$message);
							    $message = str_replace("&#8217;","'",$message);
							    $message = str_replace("&quot;",'"',$message);
							    $message = str_replace('<br>','',$message);
							    $message = str_replace('<br />','',$message);
							    $message = str_replace("&lt;","<",$message);
							    $message = str_replace("&gt;",">",$message);
							    $message = str_replace("&amp;","&",$message);
				
							    if (mail($destinataire, $tel, $message, $headers))
							    {
							    	echo '<div class="alert alert-success">'.$message_envoye.'</div>'."\n";
							    }
							    else
							    {
							    	echo '<div class="alert alert-error">'.$message_non_envoye.'</div>'."\n";
							    }
						    }
						    else
						    {
							    echo '<div class="alert alert-error">'.$message_formulaire_invalide.'</div>'."\n";
						    }
						}
						else
						{
							echo '<div class="alert alert-error">'.$verifmail.'</div>';
						}
					}
					?>
               <h2>Nous contacter </h2>
			   <p>Utilisez ce formulaire afin de nous envoyer un message par E-mail. Veillez à ce que les informations que vous entrez soient correctes afin que nous puissions vous recontacter.</p>
               
                <div class="control-group">
                    <label class="control-label" for="nom">Nom :</label>
                    <div class="controls">
                        <input id="nom" name="nom" type="text" placeholder="ex: Jean Dupont" value="<?=$nom?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Adresse E-mail :</label>
                    <div class="controls">
                        <input id="email" name="email" type="text" placeholder="ex: jean.dupont@mail.com" value="<?=$email?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="message">Votre message</label>
                    <div class="controls">
                       <textarea id="message" name="message" class="span6" ><?=$message?></textarea>
                    </div>
                </div>
                <div class="form-actions"><button type="submit" value="Envoyer" name="envoi" id="envoi" class="btn btn-info btn-large">Envoyer le message</button></div>
               
               </form>
          </div>
      </div>
  </div>
  
  <?php 
  include("includes/footer.php");?>
  <script src="js/jquery.js"></script>
  <?php include("includes/pied.php");
   ?>
