<?php
	include('conf.php');
?>	
<html>
	
	<head>
		<?php include('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="css/organiser_tournoi.css">
		<title>Organiser un tournoi</title>
	</head>

	<body>

	<div class="row space-top">
			<div class="form-grand">

				<div class="panel panel-primary">
				  	<div class="panel-heading">
				    	<h3 class="panel-title">Organiser</h3>
				  	</div>
				  	<div class="panel-body">

						<form class="form-horizontal form-grand" method="post" action="organiser_tournoi_traitement.php">
				  			<fieldset>

				    			<legend class="center">Organiser un tournoi</legend>

				    			<div class="form-group center">
				    				<p>Coordonnées</p>
						        	<input type="text" class="form-control" id="input_event_titre" name="event_titre" placeholder="Nom du tournoi">
						        	<br/>
						        	<input type="text" class="form-control" id="input_event_adresse" name="event_adresse" placeholder="Adresse du tournoi">
						        	<div class="ligne">
							        	<input type="text" class="form-control" id="input_event_code_postal" name="input_event_code_postal" placeholder="Code Postal">
							        	<input type="text" class="form-control" id="input_event_ville" name="event_ville" placeholder="Ville">
							        </div>
						    	</div>

						    	<div class="form-group center">
						    		<p>Horaires</p>

							    	<input type="text" class="form-control" id="input_event_date" name="input_event_date" placeholder="Date: Samedi 24 Juin">
						    		<div class="ligne">
							    		<input type="text" class="form-control" id="input_event_nb_heure_jeu" name="input_event_nb_heure_jeu" placeholder="Nombre d'heure de jeu">
							    		<input type="text" class="form-control" id="input_event_heure_debut" name="input_event_heure_debut" placeholder="Heure de début de l'événement">
							    		<input type="text" class="form-control" id="input_event_heure_fin" name="input_event_heure_fin" placeholder="Heure de fin de l'événement">
							    	</div>
						    	</div>

						    	<div class="form-group center">
						    		<p>Participants</p>
							        <input type="" class="form-control" id="input_event_nb_joueur_min" name="event_nb_joueur_min" placeholder="Nombre d'équipes">
						    		<div class="ligne">
							        	<input type="text" class="form-control" id="input_event_nb_joueur_min" name="event_nb_joueur_min" placeholder="Nombre de joueurs min par équipe">
							       		<input type="text" class="form-control" id="input_event_nb_joueur_max" name="event_nb_joueur_max" placeholder="Nombre de joueurs max par équipe">
						       		</div>
						    	</div>

						    	<div class="form-group center">
						    		<p>Paiement</p>
						    		<label for="radio_equipe">Prix par équipe</label>
						    		<input id="radio_equipe" type="radio" name="tarification" value="equipe">
						    		<label for="radio_joueur">Prix par joueur</label>
						    		<input id="radio_joueur" type="radio" name="tarification" value="equipe">
						    		<div id="tarif" class="ligne"> 
							    		<select class="form-control" name="tarif">
							    			<?php
							     				for ($i=0; $i < 60; $i=$i+0.5) { 
							    					?>
							    						<option value='<?php echo $i; ?>'>
							    							<?php echo $i.' € '; ?>
							    						</option>
							    					<?php
							    				}
							    			?>
							    		</select>
							    	</div>
							    	<div>
							    		<br/>
								    	<p>Tournois-soccer.fr permet de gérer les encaissements des joueurs directement depuis la plateforme. Souhaitez-vous en profiter?</p>
							    		<input id="paiement_ok" type="radio" name="paiement" value="paiement_ok">
							    		<label for="paiement_ok">Oui, ça m'enlève une grosse épine du pied</label>
							    		<input id="paiement_refus" type="radio" name="paiement" value="paiement_refus">
							    		<label for="paiement_refus">Non merci, j'ai beaucoup de courage!</label>
						    		</div>
						    		<div>
						    			<label for="input_event_rib">Si vous souhaitez recueillir les paiements par notre plate-forme, merci de nous communiquer le RIB sur lequel vous reverser les sommes collectées</label>
							        	<input type="text" class="form-control" id="input_event_rib" name="event_rib" placeholder="Optionnel: RIB: FR76 XXXX">
							        </div>
						    	</div>
						    	
						    	<div class="form-group center">
						    		<p>Descriptif</p>
						    		<input class="form-control" type="textarea" name="descriptif" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance">
						    		<!-- LOGO -->
						    	</div>

						    	<div class="form-group center">
						    		<p>Inscription</p>
							    	<input id="match_public" type="radio" name="match" value="paiement_ok">
							    	<label for="match_public">Match publique</label>
							   		<input id="match_prive" type="radio" name="paiement" value="paiement_refus">
							   		<label for="match_prive">Match privé (avec mot de passe)</label>
							   		<br/>
							   		<input type="text" class="form-control" id="input_event_pass" name="event_pass" placeholder="Mot de passe. ex:******">
						    	</div>

						    	<div class="form-group center">
				    				<button type="submit" name="submit" class="btn btn-primary btn-grand">Se connecter</button>	        	
					    		</div>
				    		</fieldset>
				    	</form>
				    </div>
				</div>
		  	</div>
		</div>
	</body>
	<script type="text/javascript" src="js/liste_complexes.js"></script>
</html>
