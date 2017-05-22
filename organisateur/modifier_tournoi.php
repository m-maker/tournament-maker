<?php
    include '../conf.php';
?>	
<html>
	
	<head>
		<?php include('head.php'); ?>
		<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
		<link rel="stylesheet" href="css/jquery_perso.css">
		<link rel="stylesheet" type="text/css" href="css/organiser_tournoi.css">

		<title>Modifier le tournoi</title>
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script type="text/javascript" src="js/datepicker.js"></script>

	</head>

	<body>
	<?php $tournoiObjet = recupObjetTournoiByID($_GET['tournoi']); 
		echo $_SESSION['id'];
		echo $tournoiObjet->event_titre;
		if ($_SESSION['id'] != $tournoiObjet->event_orga){
		    header('location: ../index.php');
		}
		else{
	?>
	<div class="row space-top">
			<div class="form-grand">
				<div class="panel panel-primary">
				  	<div class="panel-heading">
				    	<h3 class="panel-title">Modifier</h3>
				  	</div>
				  	<div class="panel-body">

						<form class="form-horizontal form-grand" method="post" action="modifier_tournoi_traitement.php?tournoi=<?php echo $_GET['tournoi']; ?>">
				  			<fieldset>

				    			<legend class="center">Modifier un tournoi</legend>
				    			<hr>
				    			<div class="form-group form-group-sm center">
				    				<p>Coordonnées</p>
						        	<input type="text" class="form-control" id="input_event_titre" name="event_titre" placeholder="Nom du tournoi" value="<?php echo $tournoiObjet->event_titre; ?>">
						        	<br/>
						        	<input type="text" class="form-control" id="input_event_lieu_nom" name="event_lieu_nom" placeholder="Nom du lieu qui accueille le tournoi" value="<?php echo $tournoiObjet->lieu_nom; ?>">
						        	<input type="text" class="form-control" id="input_event_adresse" name="event_adresse" placeholder="Adresse du tournoi" value="<?php echo $tournoiObjet->lieu_adresse_l1; ?>">
						        	<div class="ligne">
							        	<input type="text" class="form-control" id="input_event_code_postal" name="event_code_postal" placeholder="Code Postal" value="<?php echo $tournoiObjet->lieu_cp; ?>">
							        	<input type="text" class="form-control" id="input_event_ville" name="event_ville" placeholder="Ville" value="<?php echo $tournoiObjet->lieu_ville; ?>">
							        </div>
						    	</div>

						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<div class="center">
						    			<p>Horaires</p>
						    		</div>
							    	<div class="ligne espacer">
							    		<div>
							      			<p> Date du tournoi </p>
							  		  		<div class="form-group has-feedback">
    											<label class="control-label">Date</label>
   												<input class="form-control" type="text" name="event_date" id="datepicker" value="<?php echo $tournoiObjet->event_date; ?>">
    											<i class="glyphicon glyphicon-calendar form-control-feedback"></i>
											</div>
										</div>
							    		<div class="sous-section">
							    			<p>Heure du début</p>

							    			<?php
							    				$horaire_debut=explode(":", $tournoiObjet->event_heure_debut);
							    				$heure_debut = $horaire_debut[0];
							    				$minute_debut = $horaire_debut[1];
							    			?>

							    			<div class="ligne">
									    		<div>
    												<label class="control-label">Heures</label>
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
											    					<option value='<?php echo $heure; ?>' 
											    						<?php 
											    							if($heure == $heure_debut){
											    								echo "selected";
											    							}
											    					 	?>>
																		<?php echo $heure.'H'; ?>
									    							</option>
									    						<?php
									    					}
									    				?>
									    			</select>
									    		</div>
									    		<div>
    												<label class="control-label">Minutes</label>
													<select class="form-control" id="input_minute_debut" name="minute_debut">
											    			<option value="00" 
											    				<?php 
											    					if($minute_debut == "00"){
											    						echo "selected";
											    					}
											    				?>>
											    				00 min
											    			</option>
											   				<option value="15"											    				
											   					<?php 
											    					if($minute_debut == "15"){
											    						echo "selected";
											    					}
											    				?>>
											    				15 min
											    			</option>	
											   				<option value="30"											    				
											   					<?php 
											    					if($minute_debut == "30"){
											    						echo "selected";
											    					}
											    				?>>
											    				30 min
											    			</option>	
											   				<option value="45"											    				
											   					<?php 
											    					if($minute_debut == "45"){
											    						echo "selected";
											    					}
											    				?>>
											    				45 min
											    			</option>	
									    			</select>
									   			</div>
									   		</div>
										</div>
										<div id="fin_tounoi">
					    					<div>
							    				<p>Heure de fin</p>

							    			<?php
							    				$horaire_fin=explode(":", $tournoiObjet->event_heure_fin);
							    				$heure_fin = $horaire_fin[0];
							    				$minute_fin = $horaire_fin[1];
							    			?>

							    			</div>
							   				<div class="ligne">
								    			<div>
    												<label class="control-label">Heures</label>								
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
									    						<option value='<?php echo $heure; ?>'
									    							<?php 
											    						if($heure == $heure_fin){
											    							echo "selected";
											    						}
											    				 	?>>
									    							<?php echo $heure; ?>
									    						</option>
									    					<?php
									    				}
									    				?>
									    			</select>
									   			</div>
									    		<div>
    												<label class="control-label">Minutes</label>
									    			<select class="form-control" id="input_minute_fin" name="minute_fin">									<option value="00" 
											    				<?php 
											    					if($minute_fin == "00"){
											    						echo "selected";
											    					}
											    				?>>
											    				00 min
											    			</option>
											   				<option value="15"											    				
											   					<?php 
											    					if($minute_fin == "15"){
											    						echo "selected";
											    					}
											    				?>>
											    				15 min
											    			</option>	
											   				<option value="30"											    				
											   					<?php 
											    					if($minute_fin == "30"){
											    						echo "selected";
											    					}
											    				?>>
											    				30 min
											    			</option>	
											   				<option value="45"											    				
											   					<?php 
											    					if($minute_fin == "45"){
											    						echo "selected";
											    					}
											    				?>>
											    				45 min
											    			</option>	
									    			</select>
								   				</div>
								   			</div>
							 			</div>
					    			</div>
							   	</div>
							    <hr>

						    	<div class="form-group form-group-sm center">
						    	  	<p>Participants</p>
						    	  	<div class="ligne espacer">
							   	  		<div>
							   	  			<p>Nombre d'équipes</p>
							   	  			<select class="form-control" id="input_event_nb_equipes" name="event_nb_equipes" value="<?php echo $tournoiObjet->event_nb_equipes; ?>">
							   	  				<?php
						    	  					for ($i=1; $i<33; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>' 
						    	  								<?php
						    	  									if($tournoiObjet->event_nb_equipes == $i){
						    	  										echo " selected";
						    	  									} 
						    	  								?>>
						    	  								<?php echo $i.' équipes'; ?>
						     								</option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  		<div>
						    	  			<p>Nombre de joueurs min</p>
						    	  			<select class="form-control" id="input_event_joueurs_min" name="event_joueurs_min">
						    	  				<?php
						    	  					for ($i=0; $i<8; $i++) { 
						    	  						?>
						    	  							<option value='<?php echo $i; ?>' 
						    	  								<?php
						    	  									if($tournoiObjet->event_joueurs_min == $i){
						    	  										echo " selected";
						    	  									} 
						    	  								?>>
						    	  								<?php echo $i.' joueurs min'; ?>
						    								</option>
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
						    	  							<option value='<?php echo $i; ?>' 
						    	  								<?php
						    	  									if($tournoiObjet->event_joueurs_max == $i){
						    	  										echo " selected";
						    	  									} 
						    	  								?>>
						    	  								<?php echo $i.' joueurs max'; ?>
						    	 							</option>
						    	  						<?php
						    	  					}
						    	  				?>
						    	  			</select>
						     	  		</div>
						     	  	</div>
						     	</div>

						    	
						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Paiement</p>
						    		<label for="radio_equipe">Prix par équipe</label>
						    		<input id="radio_equipe" type="radio" name="event_tarification_equipe" value="1"
						    			<?php
						    				if($tournoiObjet->event_tarification_equipe == 1){
						    					echo "checked=\"checked\"";
						    				}
						    			?>>
						    		<label for="radio_joueur">Prix par joueur</label>
						    		<input id="radio_joueur" type="radio" name="event_tarification_equipe" value="0"						    		
						    			<?php
						    				if($tournoiObjet->event_tarification_equipe == 0){
						    					echo "checked=\"checked\"";
						    				}
						    			?>>
						    		<div id="tarif" class="ligne"> 
							    		<select class="form-control" name="tarif">
							    			<?php
							     				for ($i=0; $i < 60; $i=$i+0.5) { 
							    					?>
							    						<option value='<?php echo $i; ?>' 
						    	  							<?php
						    	  								if($tournoiObjet->event_tarif == $i){
						    	  									echo " selected";
						    	  								} 
						    	  							?>>
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
							    		<input id="paiement_ok" type="radio" name="paiement" value="1"
							    			<?php
						    					if($tournoiObjet->event_paiement == 1){
						    						echo " checked=\"checked\"";
						    					}
						    				?>>
							    		<label for="paiement_ok">Oui, ça m'enlève une grosse épine du pied</label>
							    		<br/>
							    		<input id="paiement_refus" type="radio" name="paiement" value="0"
							    			<?php
						    					if($tournoiObjet->event_paiement == 0){
						    						echo " checked=\"checked\"";
						    					}
						    				?>>
							    		<label for="paiement_refus">Non merci, j'ai beaucoup de courage!</label>
						    		</div>
						    		<br/>
						    		<div>
						    			<?php 
						    				$req_rib = $db->prepare('SELECT * FROM rib WHERE rib_id = :rib_id');
						    				$req_rib->execute(array(
						    					'rib_id' => $tournoiObjet->event_rib_id
						    					));
						    				$res_rib = $req_rib->fetch();
						    			?>
						    			<label for="input_event_rib">Si vous souhaitez recueillir les paiements par notre plate-forme, merci de nous communiquer le RIB sur lequel vous reverser les sommes collectées</label>
							        	<input type="text" class="form-control" id="input_event_rib" name="event_rib" placeholder="Optionnel: RIB: FR76 XXXX" value="<?php echo $res_rib['rib_code']; ?>">
							        </div>
						    	</div>
						    	
						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Descriptif</p>
						    		<input class="form-control" type="textarea" name="event_descriptif" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance" value="<?php echo $tournoiObjet->event_descriptif; ?>">
						    		<!-- LOGO -->
						    	</div>

						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Inscription</p>
							    	<input id="match_public" type="radio" name="restriction" value="0"
							    			<?php
						    					if($tournoiObjet->event_prive == 1){
						    						echo "checked=\"checked\"";
						    					}
						    				?>>
							    	<label for="match_public">Match publique</label>
							   		<input id="match_prive" type="radio" name="restriction" value="1"
							    			<?php
						    					if($tournoiObjet->event_prive == 0){
						    						echo "checked=\"checked\"";
						    					}
						    				?>>
							   		<label for="match_prive">Match privé (avec mot de passe)</label>
							   		<br/>
							   		<input type="text" class="form-control" id="input_event_pass" name="event_pass" placeholder="Mot de passe. ex:******" value="<?php echo $tournoiObjet->event_pass; ?>">
						    	</div>

						    	<div class="form-group form-group-sm center">
				    				<button type="submit" name="submit" class="btn btn-primary btn-grand">Se connecter</button>	        	
					    		</div>
				    		</fieldset>
				    	</form>
				    </div>
				</div>
		  	</div>
		</div>
	<?php
		}
	?>
	</body>
</html>
