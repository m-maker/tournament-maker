<?php
	include('conf.php');
?>	
<html>
	
	<head>
		<?php include('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="css/organiser_tournoi.css">
		<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
		<link rel="stylesheet" href="css/jquery_perso.css">

		<title>Organiser un tournoi</title>
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script type="text/javascript" src="js/datepicker.js"></script>

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

				    			<hr>
				    			<div class="form-group center">
				    				<p>Coordonnées</p>
						        	<input type="text" class="form-control" id="input_event_titre" name="event_titre" placeholder="Nom du tournoi">
						        	<br/>
						        	<input type="text" class="form-control" id="input_event_lieu_nom" name="event_lieu_nom" placeholder="Nom du lieu qui accueil le tournoi">
						        	<input type="text" class="form-control" id="input_event_adresse" name="event_adresse" placeholder="Adresse du tournoi">
						        	<div class="ligne">
							        	<input type="text" class="form-control" id="input_event_code_postal" name="event_code_postal" placeholder="Code Postal">
							        	<input type="text" class="form-control" id="input_event_ville" name="event_ville" placeholder="Ville">
							        </div>
						    	</div>

						    	<hr>
						    	<div class="form-group center">
						    		<p>Horaires</p>
						    		<div class="ligne espacer">
						    			<div id="debut_tounoi" class="ligne">
						    				<div>
						    					<input class="form-control" type="text" name="event_date" id="datepicker">
						    				</div>
						    				<div>
						    					<p>Heure du début</p>
						    					<div class="ligne">
								    				<div>
									      				<p>Heure</p>
										    			<select class="form-control" id="input_heure_debut" name="heure_debut">
										    				<?php
										    					for ($i=0; $i<25 ; $i++) { 
										    						
										    						if ($i<10){
										    							$heure = '0'.$i;
										    						}
										    						else {
										    							$heure = $i;
										    						}

										    						?>
										    							<option value='<?php echo $heure; ?>'>
										    								<?php echo $heure.'H'; ?>
										    							</option>
										    						<?php
										    					}
										    				?>
										    			</select>
										    		</div>
										    		<div>
										    			<p>Minutes</p>
									    					<select class="form-control" id="input_minute_debut" name="minute_debut">
											    				<option value="00">00 min</option>
											    				<option value="15">15 min</option>	
											    				<option value="30">30 min</option>	
											    				<option value="45">45 min</option>	
									    					</select>
									    			</div>
									    		</div>
								    		</div>
								    	</div>

						    			<div id="fin_tounoi" class="ligne">
						    				<div>
						    					<p>Heure de fin</p>
						    					<div class="ligne">
							    					<div>
							    						<p>Heure</p>
										    			<select class="form-control" id="input_heure_fin" name="heure_fin">
											    			<?php
											    			for ($i=0; $i<25 ; $i++) { 
											    				if ($i<10){
											    					$heure = '0'.$i;
											    				}
											    				else {
											    					$heure = $i;
											    				}
											    					?>
										    						<option value='<?php echo $heure; ?>'>
										    							<?php echo $heure; ?>
										    						</option>
										    					<?php
										    				}
										    				?>
										    			</select>
									    			</div>
										    		<div>
										    			<p>Minutes</p>
										    			<select class="form-control" id="input_minute_fin" name="minute_fin">
										    				<option value="00">00 min</option>
										    				<option value="15">15 min</option>	
										    				<option value="30">30 min</option>	
										    				<option value="45">45 min</option>	
										    			</select>
								    				</div>
								    			</div>
							    			</div>
						    			</div>
						    		</div>
							    </div>
							    <hr>
						    	<div class="form-group center">
						    	  	<p>Participants</p>
						    	  	<div class="ligne espacer">
							   	  		<div>
							   	  			<p>Nombre d'équipes</p>
							   	  			<select class="form-control" id="input_event_nb_equipes" name="event_nb_equipes">
							   	  				<?php
						    	  					for ($i=1; $i<33; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' équipes'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  		<div>
						    	  			<p>Nombre de joueurs max</p>
						    	  			<select class="form-control" id="input_event_joueurs_min" name="event_joueurs_min">
						    	  				<?php
						    	  					for ($i=0; $i<8; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' joueurs max'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  		<div>
						    	  			<p>Nombre de joueurs max</p>
						    	  			<select class="form-control" id="input_event_joueurs_max" name="event_joueurs_max">
						    	  				<?php
						    	  					for ($i=5; $i<10; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>'><?php echo $i.' joueurs max'; ?></option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  	</div>
						     	</div>

						    	
						    	<hr>
						    	<div class="form-group center">
						    		<p>Paiement</p>
						    		<label for="radio_equipe">Prix par équipe</label>
						    		<input id="radio_equipe" type="radio" name="event_tarification_equipe" value="1">
						    		<label for="radio_joueur">Prix par joueur</label>
						    		<input id="radio_joueur" type="radio" name="event_tarification_equipe" value="0">
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
							    		<input id="paiement_ok" type="radio" name="paiement" value="1">
							    		<label for="paiement_ok">Oui, ça m'enlève une grosse épine du pied</label>
							    		<input id="paiement_refus" type="radio" name="paiement" value="0">
							    		<label for="paiement_refus">Non merci, j'ai beaucoup de courage!</label>
						    		</div>
						    		<div>
						    			<label for="input_event_rib">Si vous souhaitez recueillir les paiements par notre plate-forme, merci de nous communiquer le RIB sur lequel vous reverser les sommes collectées</label>
							        	<input type="text" class="form-control" id="input_event_rib" name="event_rib" placeholder="Optionnel: RIB: FR76 XXXX">
							        </div>
						    	</div>
						    	
						    	<hr>
						    	<div class="form-group center">
						    		<p>Descriptif</p>
						    		<input class="form-control" type="textarea" name="event_descriptif" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance">
						    		<!-- LOGO -->
						    	</div>

						    	<hr>
						    	<div class="form-group center">
						    		<p>Inscription</p>
							    	<input id="match_public" type="radio" name="restriction" value="0">
							    	<label for="match_public">Match publique</label>
							   		<input id="match_prive" type="radio" name="restriction" value="1">
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
</html>
