<?php
session_start();
include("includes.php");

include("../includes/class.newsmanager.php");
VerifConnection($bdd,$_SESSION,9);
	
$breadcrumbs='<a href="index.php">Index de l\'administration</a> <div class="breadcrumb_divider"></div> 
<a class="current">Envoyer un mail</a>';
	
include("includes/header.php");

$message=""; $sujet=""; $contenu="";
if(isset($_POST["envoyer"]))
{
	$verif_titre=Verif($_POST["sujet"],"Sujet",3,64);
	$verif_contenu=Verif($_POST["contenu"],"Contenu",3,64);
	$sujet=$_POST["sujet"];
	$contenu=$_POST["contenu"];
	if($verif_titre==1)
	{
		if($verif_contenu==1)
		{
			$niveaux=$_POST["options"];
			$stmt = $bdd->prepare('SELECT * FROM personnes');
			$stmt->execute();
			while($donnees=$stmt->fetch())
			{
				$envoi=false;
				$stmt2 = $bdd->prepare('SELECT * FROM typepersonnepersonne WHERE typeperonne_idpersonnes = :id_personne');
			    $stmt2->execute(array("id_personne"=>$donnees["idpersonne"]));
			    while($donnees2=$stmt2->fetch())
			    {
					foreach($niveaux as &$niveau)
					{
						if($envoi!=true)
						{
							if($niveau==$donnees2["typepersonnepersonne_cataloguetypepersonne"])
							{
								$envoi=true;
								$headers  = 'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								$headers .= 'From: ROPI <contact@ropi.be>' . "\r\n";
								
								$sujet=$_POST["sujet"];	
								$var=$_POST["contenu"];
								$adresse=$donnees["mailpersonnes"];
								
								mail($adresse, $sujet, $var, $headers);

							}
						}
					}
					
				}
			    $stmt2->closeCursor();
			}
			$stmt->closeCursor();
		}
		else
		    $message='<h4 class="alert_error">'.$verif_contenu.'</h4>';
	}
	else
	    $message='<h4 class="alert_error">'.$verif_titre.'</h4>';
}

?>
	<section id="main" class="column">		
		<article class="module width_full">
			<header><h3>Rédaction d'un E-mail</h3></header>
			<div class="module_content">
            	<p>Dans cette page vous pouvez envoyer un mail aux membres du site.</p>
                <h3>Vérifiez bien le contenu avant d'envoyer</h3>
			</div>
		</article><!-- end of stats article -->
		
		
		<div class="clear"></div>
		
		<article class="module width_full">
			<header><h3>Envoyer un mail</h3></header>
				<div class="module_content">
                    <?php echo $message?>
                    <form name="mail" id="mail" action="#" method="post">
                        <fieldset> 
					        <label>À qui envoyer le mail ?</label>
                            <?php
							$stmt = $bdd->prepare('SELECT * FROM cataloguetypersonne ORDER BY idcataloguetypersonne');
							$stmt->execute();
							while($donnees=$stmt->fetch())
							{
								echo '<br /><p><label><input type="checkbox" name="options[]" value="'.$donnees["idcataloguetypersonne"].'">'.$donnees["cataloguetypersonnelabel"].'</label></p>';
							}
							$stmt->closeCursor();
							?>
						</fieldset>
                        <fieldset> 
					        <label>Sujet</label>
                            <input type="text" name="sujet" id="sujet" value="<?=$sujet?>" />
						</fieldset>
                        <fieldset>
							<label for="contenu">Contentu</label>
							<textarea id="contenu" name="contenu" rows="12" style="height:300px;"><?=$contenu?></textarea>
						</fieldset>
                   <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="envoyer" value="Envoyer le mail"  class="alt_btn">
</form>
				</div>
			</footer>
		</article><!-- end of post new article -->


		<div class="spacer"></div>
	</section>

<?php include("includes/footer.php");?>
