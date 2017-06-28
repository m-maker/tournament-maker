<?php
	include('../conf.php');
	$db = connexionBdd();
    $_SESSION['gerant_lieu_id'] = 1;
    $format = 'Y-m-d';
	// $parametres_fonction = $_POST['nom_fonction'];
	$nom_fonction = "planning_complexe";
	$parametres_fonction;
	// $parametres_fonction = $_POST['parametres_fonction'];
	if (!isset($_POST['gerant_lieu_id']) AND !isset($_SESSION['gerant_lieu_id'])){
		header('locaton:index.php');
	}
	elseif (isset($_POST['gerant_lieu_id'])){
		$_SESSION['gerant_lieu_id'] = $_POST['gerant_lieu_id'];
	}
	elseif (!isset($_SESSION['gerant_lieu_id'])){
		location('index.php');
	}
		
	if ($nom_fonction = "planning_complexe"){

		$parametres_fonction['lieu_id'] = $_SESSION['gerant_lieu_id'];
		$heure_min = 10;
		$heure_max = 23.0;
		if (isset($_POST['horaire'])){
			$date_min = DateTime::createFromFormat('Y-m-j H:i:s', $_POST['jour']);
		}
		else{
			$date_min = new DateTime;
		}
			
		$date_max = clone($date_min);
		$date_max->add( new DateInterval('P0D'));

		// on récupère un tableau avec la liste des terrains.
		$req_terrains = $db->prepare('SELECT * FROM terrains WHERE terrain_lieu_id = :lieu_id');
		$req_terrains->execute(array(
			'lieu_id' => $parametres_fonction['lieu_id']
			));
		$res_terrains = $req_terrains->fetchAll();
		$nb_terrain = count($res_terrains);

		// Pour chaque terrain, on y associes ses créneaux.
		foreach ($res_terrains as $key => $val) {

			// Récupération des créneaux pour un terrain.
			$req_liste_creneaux = $db->prepare('SELECT * FROM creneaux WHERE creneau_terrain_id = :terrain_id');
			$req_liste_creneaux->execute(array(
				'terrain_id' => $val['id']
				));
			$res_liste_creneaux = $req_liste_creneaux->fetchAll();

			// Ajout du tableau des créneaux dans le tableau des terrains
			$res_terrains[$key]['creneaux'] = $res_liste_creneaux;
			//on récupère la liste des events et on l'ajoute au tableau des terrains
			$liste_event_terrain = liste_event_terrain($val['id']);
			$res_terrains[$key]['liste_event'] = $liste_event_terrain;

			// On génère un champ créneaux_morts contenant toutes les demi-heure à ne pas afficher à l'écran car elles sont regroupées dans un event. ex: 19h00-21h00 -> 1 crénau (19h00-19h30) + 3 créneaux morts
			foreach ($res_liste_creneaux as $key_creneau => $value_creneau) {
				if ($value_creneau['creneau_statut_id'] == 2){
					$date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $value_creneau['creneau_datetime']);
					$date_debut->add( new DateInterval ('PT30M'));
					$date_fin =	DateTime::createFromFormat('Y-m-j H:i:s', $value_creneau['creneau_datetime_fin']);
					while ($date_debut < $date_fin){
						$liste_creneaux_morts[$date_debut->format('Y-m-j H:i:s')] = 1;
						$date_debut->add( new DateInterval ('PT30M'));
					}
				}
				
			}
			if (isset($liste_creneaux_morts)){
				$res_terrains[$key]['creneaux_morts'] = $liste_creneaux_morts;
				unset($liste_creneaux_morts);
			}

		}
	}
	?>
		    <div class="tableau"> 
				<table>
					<?php 
						entete_complexe($date_min, $date_max,  $parametres_fonction['lieu_id'], $res_terrains);
						//$nom_fonction($date_min, $date_max, $lieu_id, $res_nb_terrains);
						for ($heure = $heure_min; $heure < $heure_max; $heure= $heure + 0.5) {
							?>
								<tr>
									<?php
										$jour = clone($date_min);								
										while ($jour <= $date_max){
											if ( intval($heure) == $heure){
												$minutes = "00";
											}
											else{
												$minutes = "30";
											}
											$datetime_string = $jour->format('Y-n-j').' '.intval($heure).':'.$minutes.':00';
											$date_case = DateTime::createFromFormat('Y-n-j H:i:s', $datetime_string);
											//$nom_fonction($parametres_fonction);$datetime_string = '2017-06-23 17:00:00';
											$date_case_string = $date_case->format('Y-m-j H:i:s');
											case_complexe($date_case_string, $res_terrains);
											unset($date_case);
											$jour->add( new DateInterval('P1D'));
										}
										unset($jour);
									?>
								</tr>
							<?php
						} 
					?>
				</table>
			</div>
		</div>
        
    </div>
</div>



<?php
	function entete_complexe($jourmin, $jourmax, $lieu_id, $tab_terrains){
		$db = connexionBdd();
		$req_format_terrains = $db->prepare('SELECT * FROM terrain_format');
		$req_format_terrains->execute();
		$format_terrains = $req_format_terrains->fetchAll();
		?>
			<tr class="entete_complexe_jour">
				<?php	
					$jour = clone ($jourmin);
					while ($jour <= $jourmax){
						?>
							<th  colspan="2">
								<div > <?php echo $jour->format("d/m"); ?> </div>
							</th>
						<?php
						$jour->add( new DateInterval('P1D'));
					}
					unset($jour);
				?>
			</tr>
			<tr>
				<?php	
					$jour = clone ($jourmin);
					while ($jour <= $jourmax){
						foreach ($tab_terrains as $terrain) {
							?>
								<td class="">
									<?php echo $terrain['terrain_nom'];
									?>
									
								</td>
							<?php
						}
						$jour->add( new DateInterval('P1D'));
					}
					unset($jour);
				?>
			</tr>
		<?php
	}							

	function case_complexe ($date_heure, $liste_terrains){
		//	1 = indisponible / 2 = réservé / 9 = morts
		foreach ($liste_terrains as $terrain => $val) {
			$creneau_rempli = 0;
		// On recherche la clé de la ligne dans laquelle le creneau_datetime vaut la date
			foreach ($val['creneaux'] as $creneau => $value) {

				// s'il s'agit du créneau
			 	if ($value['creneau_datetime'] == $date_heure){


			 		if ($value['creneau_statut_id'] == 1) {
						?>
							<td style="margin: 0px; padding: 0px; ">
								<button  style="margin: 0px; padding: 0px; " class="boutton" data-toggle="popover" data-trigger="focus"  title="Participants" container='body' data-html="true" data-content="coucou
						 			<?php //planning_popover(); ?>">				 		
									créneau fermé
								</button>
							</td>
						<?php
					$creneau_rempli = 1;		 			
			 		}

			 		elseif ($value['creneau_statut_id'] == 2) {
			 			$date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $date_heure);
			 			$date_fin = DateTime::createFromFormat('Y-m-j H:i:s', $value['creneau_datetime_fin']);
						$demi_heure = DateTime::createFromFormat('i', 30);
						$nb_demi_heure = 0;
						while ($date_debut < $date_fin){
							$nb_demi_heure = $nb_demi_heure + 1;
							$date_debut->add(new DateInterval('PT30M'));
						}
						unset($date_debut);
						unset($date_fin);
			 			$date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $date_heure);
			 			$date_fin = DateTime::createFromFormat('Y-m-j H:i:s', $value['creneau_datetime_fin']);
						$hauteur = 23*$nb_demi_heure;
						$event;
						foreach ($val['liste_event'] as $event_key => $event_value) {
							if ($event_value['creneau_event_id'] == $value['creneau_event_id']){
								$event = $event_value;
							}
						}
						?>

							<td rowspan="<?php echo $nb_demi_heure; ?>" style="height: <?php echo $hauteur; ?>;">
								<button  class="boutton creneau_match" data-toggle="modal" data-target="#modal_match_<?php echo $value['creneau_event_id']; ?>">
								créneau réservé <?php echo $nb_demi_heure; ?>
								</button>

									 <!-- *************************************** 		Modal, pour les tournois /matchs   	***************************************    -->
									


									<div class="modal fade" id="modal_match_<?php echo $value['creneau_event_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
									    	<div class="modal-content">

									        	<!-- *********    Header de la modal       ***************    -->
									      		<div class="modal-header">
									        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										        			<p id="modal_match_date">
										        				<?php echo $date_debut->format('d-m'); ?>
										        			</p>
										        			<p id="modal_match_heure">
										        				<span id="modal_match_heure_debut"> 
										        					<?php echo $date_debut->format('H').'H'.$date_debut->format('i');; ?>
										        				</span>
										        				<span> - </span>
										        				<span id="modal_match_heure_fin">
										        					<?php echo $date_fin->format('H').'H'.$date_fin->format('i'); ?>
										        				</span>
										        			</p>
									        	</div>


									        	<div class="modal-body">

									        		<!-- ********************** 		Modal version tournois   	************************    -->
									        		<div id="modal_match_tournoi" style="">

									      				<div class="modal_match_tournoi_info">
										        			<p>	
										        				<span> Prix : </span>
										        				<span id="modal_match_prix"> <?php echo $event['event_tarif']; ?></span>
										        				<span> € </span>
										        			</p>
										        			<br/>
										        			<p>
										        				<span>descriptif : </span><br/>
										        				<span id="modal_match_descriptif"> <?php echo $event['event_descriptif']; ?> </span>
										        			</p>
										        		</div>

										        		<hr/>
										        		
										        		<div class="modal_match_tournoi_participants">       
								                            <?php 
																$leTournoi = recupObjetTournoiByID($event['event_id']);
								                                $equipes_completes = recupEquipesCompletes($event['event_id'], $leTournoi->event_joueurs_min); 
						                                        $nb_equipes_completes = count($equipes_completes);
					                                        ?>
								                            <p><span class="glyphicon glyphicon-user"></span><span> <?php echo $nb_equipes_completes . ' / ' . $leTournoi->event_nb_equipes; ?></span> équipes complètes</p>
								                        <?php 
								                                        $equipes_completes = recupEquipesCompletes($event['event_id'], $leTournoi->event_joueurs_min); 
								                                        if (!empty($equipes_completes)){
								                                            foreach ($equipes_completes as $uneEquipe) { 
								                                                ?>
								                                                    <div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
								                                                                <span><?php echo $uneEquipe["team_nom"]; ?></span>
								                                                                <span><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</span>
								                                                        <?php 
								                                                            $joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
								                                                            $i = 2;
								                                                         ?>
								                                                        <div class="equipe-joueurs">
								                                                            <div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
								                                                                <?php
								                                                                    foreach ($joueurs_equipe as $unJoueur) {
								                                                                        if ($unJoueur["em_membre_paye"] == 1) {
								                                                                            $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; 
								                                                                        }
								                                                                        else { 
								                                                                            $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; 
								                                                                        }
								                                                                        ?>
								                                                                            <div class="col-md-6 un-joueur">
								                                                                                <?php echo $unJoueur["membre_pseudo"]; ?><br />
								                                                                                <?php echo $unJoueur["statut_nom"]; ?>
								                                                                                <span class="statut"><?php echo $paye; ?></span>
								                                                                            </div>
								                                                                        <?php 
								                                                                    }
								                                                                ?>
								                                                           </div>
								                                                        </div>
								                                                    </div>
								                                                <?php
								                                            }
								                                        }
								                                        else{
								                                        } 

								                                    // Partie pour les équipes incomplètes.    
								                                   	$equipes_incompletes = recupEquipesIncompletes($event['event_id'], $leTournoi->event_joueurs_min);
								                                    $nb_equipes_incompletes = count($equipes_incompletes);
								                                        ?>
								                                        <br/>
								                                        <p><span class="glyphicon glyphicon-user"></span><span><?php echo $nb_equipes_incompletes?></span> équipes incomplètes</p>
								                                    <?php
								                                        
								                                        if (!empty($equipes_incompletes)){
								                                            foreach ($equipes_incompletes as $uneEquipe) {
								                                                ?>
								                                                    <div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
								                                                        <span><?php echo $uneEquipe["team_nom"]; ?></span>
								                                                        <span><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</span>
								                                                        <?php 
								                                                            $joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
								                                                            $i = 2;
								                                                        ?>
								                                                        <div class="equipe-joueurs">
								                                                            <div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
								                                                                <?php
								                                                                    foreach ($joueurs_equipe as $unJoueur) {
								                                                                        if ($unJoueur["em_membre_paye"] == 1) {
								                                                                            $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; 
								                                                                        }
								                                                                        else { 
								                                                                            $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; 
								                                                                        }
								                                                                        ?>
								                                                                            <div class="equipe_joueurs_detail">
								                                                                                <div>
								                                                                                    <?php echo $unJoueur["membre_pseudo"]; ?><br />
								                                                                                    <?php echo $unJoueur["statut_nom"]; ?>
								                                                                                </div>
								                                                                                <div>
								                                                                                    <p class="statut"><?php echo $paye; ?></p>
								                                                                                </div>
								                                                                            </div>
								                                                                        <?php 
								                                                                    } 
								                                                                ?>
								                                                            </div>
								                                                        </div>
								                                                    </div>
								                                                    <hr/>
								                                                <?php
								                                            }
								                                        }
								                                        else{
								                                        } 
								                                    ?> 
								                                </div>
								                                <hr/>
								                                <div class="row mod-tournoi" id="m-<?php echo $event["event_id"]; ?>">
																	<div class="col-md-3">
									                                   	<a href="mur.php?tournoi=<?php echo $event['event_id']; ?>">
									                                        <button class="btn btn-default"><span class="glyphicon glyphicon-zoom-in"></span> Publier un message</button>
									                                    </a>
									                                </div>
									                                <div class="col-md-3">
																		<a href="modifier_tournoi.php?id=<?php echo $event['event_id']; ?>">
																			<button class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
																		</a>
																	</div>
																	<div class="col-md-3">
																		<a href="gestion_equipes.php?tournoi=<?php echo $event['event_id']; ?>">
																			<button class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> Gerer les equipes</button>
																		</a>
																	</div>
									                                <div class="col-md-3">
							                                            <a href="paiements.php?tournoi=<?php echo $event['event_id']; ?>">
						                                                    <button class="btn btn-default"><span class="glyphicon glyphicon-eur"></span> Encaissements</button>
								                                        </a>	
							                                    	</div>
																</div>
																<hr/>    
								                                <div class="cadre_contenu_fdt">
								                                    <div class="cont_liste-msg-tournoi">
								                		    			<?php $messages = recupMessagesMur($leTournoi->event_id);
								                    		    			foreach ($messages as $unMessage) { 
								                                                ?>
								                        			    			<div class="msg-cont">
								                        			    				<?php 
								                                                            echo $unMessage["mur_contenu"];
								                                                            if ($unMessage["membre_id"] == $_SESSION["id"]) {
								                                                                echo '<span class="delete-msg"><a href="delete_msg.php?type=0&id=' . $unMessage["mur_id"] . '&tournoi=' . $leTournoi->event_id . '">X</a></span>';
								                                                            }
								                                                        ?>
								                        			    				<div class="sign-msg">
								                                                            <span class="sign-msg-date"> Le <?php echo $unMessage["mur_date"]; ?></span>
								                                                            <br/>
								                        			    					<span class="sign-msg-membre"> Par <?php echo $unMessage["membre_pseudo"]; ?></span>
								                        			    				</div>
								                        			    			</div>
								                                                    <hr style="margin-right: 30%; margin-left: 30%; margin-bottom: 5px;"/>
								                                                <?php
								                                            }
								                                        ?>
								                                    </div>
								                                </div>
																			
								                                        <a href="suppr_tournoi.php?id=<?php echo $event['event_id']; ?>" style="margin: auto;">
								                                            <button class="btn btn-default btn-grand" style=" background: slategrey;"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
								                                        </a>
								        		    		</div>
										        		</div>
									        		</div>
									        	</div>
									        </div>
									    </div>
									</div>
							</td>
						<?php
					$creneau_rempli = 1;
					}
				}
			}
			if($creneau_rempli == 0){
				$creneau_mort = 0;
				if (isset($val['creneaux_morts'])){	
					foreach ($val['creneaux_morts'] as $key_morts => $value_morts) {
						if ($key_morts == $date_heure){
						$creneau_mort = 1;
						}	
					}
				}
				if ($creneau_mort == 1){
				}
				else{
					?>
						<td class="creneau_indispo " style="margin: 0px; padding: 0px; ">
							<button type="button" style="margin: 0px; padding: 0px; " class="boutton" data-toggle="modal" data-target="#modal_form_1">				 		 
								<?php 
									$ex = explode(" ", $date_heure); 
									$ex2 = explode(":", $ex[1]);
									echo $ex2[0].':'.$ex2[1];
								?>
								<input type="hidden" class="creneau_terrain_id" value="<?php echo $val['id']; ?>">
								<input type="hidden" class="creneau_heure_debut" value="<?php echo $ex2[0].':'.$ex2[1]; ?>">
							</button>
							
						</td>
					<?php
				}
			}
		}
	}
?>




<div class="modal fade" id="modal_form_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<form action="planning_organiser_event_traitement.php" method="post">
	<input type="hidden" id="input_event_date" name="event_date" value="">
	<input type="hidden" id="input_heure_debut" name="heure_debut" value="">
	<input type="hidden" id="input_terrain_id" name="terrain_id" value="">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h2 class="modal-title" id="myModalLabel"></h2>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	</div>
        	<div class="modal-body">

				<div id="div_type_event">
						<select class="form-control" style="padding: 1px; height: 20px; margin: auto;" id="type_event" name="type_event">
							<option value ="match">Match ouvert à tous</option>
							<option value ="rencontre">Rencontre d'équipes</option>
							<!-- <option value ="libre">Ouvrir le créneau pour les réservations</option> -->
						</select>
					</span>
				</div>

				<div class="modal_horaire">
					<hr/>
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6">
								<span>Heure de début:  </span> <br/>
								<span id="modal_heure_debut"></span>
							</div>
							<div class="col-sm-6">
								<span>Heure de fin: </span> <br/>
								<select class="form-control" id="input_heure_fin_match" style="padding: 1px; height: 20px;" name="modal_heure_fin">
									<?php
										for ($i=8; $i <= 24; $i = $i +0.5) {
											if (intval($i) == $i){
												if ($i < 10){
													$modal_heure_fin = '0'.$i.':00';
												}	
												else{
													$modal_heure_fin = $i.':00';
												}
											}
											else{
												if ($i < 10){
													$modal_heure_fin = '0'.intval($i).':30';
												}	
												else{
													$modal_heure_fin = intval($i).':30';
												}	
											}

											?>
												<option value="<?php echo $modal_heure_fin; ?>"> <?php echo $modal_heure_fin; ?> </option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>

				<hr/>

				<div id="modal_effectif">
					<div id="modal_effectif_match" class="container-fluid affiche">
						<div class="row">
							<div class="col-sm-6">
								<span>Nombre de joueurs requis: </span><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_requis">
										<option value="8"> 8 </option>
										<option value="10"> 10 </option>
										<option value="12"> 12 </option>
									</select>
								</span>
							</div>
							<div class="col-sm-6">
								<span>Nombre de joueurs déjà présents: </span>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_presents">
										<?php
											for ($i=0; $i <= 12 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php

											}
										?>
									</select>		
								</span>
							</div>
						</div>
					</div>

					<div id="modal_effectif_rencontre" class="container-fluid" style="display: none;">
						<div class="row">
							<div class="col-sm-6">
								<span>Nombre d'équipes maximum: </span> <br/><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="nb_equipes">
										<?php
											for ($i=2; $i <= 16 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php
											}
										?>
									</select>
								</span>
							</div>
							<div class="col-sm-6">
								<span>Nombre de joueurs minimum par équipe: </span><br/>
								<span>
									<select class="form-control" style="padding: 1px; height: 20px;" name="joueurs_equipe_min">
										<?php
											for ($i=0; $i <= 7 ; $i++) { 
												?> 
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
												<?php
											}
										?>
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>

				<hr/>

				<div id="modal_prix" class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<label style="margin-right: 2%;" for="radio_equipe">Prix par équipe
			    			<input id="radio_equipe" type="radio" name="event_tarification_equipe" value="1">
                        </label>
                        <br/>
						<label for="radio_joueur">Prix par joueur
							<input id="radio_joueur" type="radio" name="event_tarification_equipe" value="0" checked="checked">
	                    </label>
					</div>
					<div id="tarif" class="col-sm-6"> 
						<select class="form-control" style="padding: 1px; height: 20px;" name="tarif">
			    			<?php
							    for ($i=5; $i < 60; $i=$i+0.5) { 
									?>
							    	<option value='<?php echo $i; ?>'>
											<?php echo $i.' € '; ?>
							    		</option>
									<?php
							    }
							?>
						</select>
					</div>
				</div>
				</div>
				<hr/>

				<div id="cb" class="container-fluid">
					<span>Paiement en ligne: </span>
					<input class="pay-clic" id="paiement_ok" type="radio" name="paiement" value="1" checked="checked">
					<label style="margin-right: 2%;"' for="paiement_ok">Oui</label>
					<input class="pay-clic" id="paiement_refus" type="radio" name="paiement" value="0">
					<label for="paiement_refus">Non</label>
				</div>
				<div id="section-rib_match" class="espace-top" >
                    <div class="espace-bot">
                        Selectionnez un compte :
                        <select style="padding: 1px; height: 20px;" name="select-compte" id="select-compte-match" class="form-control">
                          	<?php 
                          		foreach ($liste_comptes as $unCompte){ 
                           			?>
                       				<option value="<?php echo $unCompte["compte_id"]; ?>"><?php echo $unCompte['compte_nom'] . ' ' . $unCompte["compte_prenom"] . ' - ' . $unCompte['compte_rib_iban']; ?></option>
                                    <?php 
                                }
                            ?>
                        	<option value="new" id="opt-new-match">Nouveau compte..</option>
                        </select>
                    </div>
                                        <div id="new-compte-match" style="display: none;">
                                            <label for="input_event_rib ">Création d'un nouveau compte</label>
                                            <div class="ligne">
                                                <input type="text" class="form-control" style="padding: 1px; height: 20px;" id="input_compte_nom_match" name="compte_nom" placeholder="Nom du titulaire du compte">
                                                <input type="text" class="form-control" style="padding: 1px; height: 20px;" id="input_compte_prenom_match" name="compte_prenom" placeholder="Prénom du titulaire du compte">
                                            </div>
                                            <div class="ligne">
                                                <select class="form-control align-select" style="padding: 1px; height: 20px;" name="jour">
                                                    <optgroup label="Jour de naissance"></optgroup>
                                                    <?php for ($i = 1; $i < 31; $i++) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control align-select" name="mois" placeholder="Mois">
                                                    <optgroup label="Mois de naissance"></optgroup>
                                                    <?php foreach ($tab_dates as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select class="form-control align-select" name="annee" placeholder="Année">
                                                    <optgroup label="Année de naissance"></optgroup>
                                                    <?php for ($i = date("Y") - 19; $i > date("Y") - 100; $i--) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" id=input_compte_adresse_l1_match" name="compte_adresse" placeholder="Adresse du titulaire du compte">
                                            <input type="text" class="form-control" id="input_compte_adresse_l2_match" name="compte_adresse_2" placeholder="Complément d'adresse (Optionnel)">
                                            <div class="ligne">
                                                <input type="text" class="form-control" id="input_compte_code_postal_match" name="compte_cp" placeholder="Code Postal du titulaire du compte">
                                                <input type="text" class="form-control" id="input_compte_ville_match" name="compte_ville" placeholder="Ville du tutulaire du compte">
                                            </div>
                                            <input type="text" class="form-control" id="input_event_rib_match" name="compte_rib_bic" placeholder="Code BIC : DAAEFRPP (Optionnel)">
                                            <input type="text" class="form-control" id="input_event_rib_iban_match" name="compte_rib_iban" placeholder="Code IBAN : FR763XXXXXXXXXXX4567890185">
                                        </div>
                                    </div>
				<hr/>
				
				<div class="form-group center">
		    		<p>Descriptif</p>
                    <textarea class="form-control" name="event_descriptif" rows="3" style="width: 80%; margin: auto;" placeholder="Match organisé par ADN-five, tous niveaux acceptés et super ambiance"></textarea>
			  		<!-- LOGO -->
                   	<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    <!--<label for="file" class="label-file">Choisir une icone pour le tournoi</label>
                    <input type="file" name="icone" id="file" style="display: none;"/>-->
				</div>

						    	<hr>
						    	<div class="form-group form-group-sm center">
						    		<p>Inscription</p>
							    	<input id="match_public" class="clic-radio" type="radio" name="restriction" value="0" checked="checked">
							    	<label for="match_public">Match publique</label>
							   		<input id="match_prive" class="clic-radio" type="radio" name="restriction" value="1">
							   		<label for="match_prive">Match privé (avec mot de passe)</label>
							   		<br/>
							   		<input style="display: none;" type="text" class="form-control" id="input_event_pass" name="event_pass" placeholder="Mot de passe. ex:******">
						    	</div>


	<input type="submit" name="envoyer">

			</div>
		</div>

	</div>

	</form>
</div>


<?php
	function planning_popover(){
		?>
			<div>
				<span> De XX:HH à <input type="time" name="heure_fin"></span>
			</div>
		<?php
		
	}
?>	


        <!-- FOOTER -->
		<?php include('footer.php') ?>
<script type="text/javascript">

$(document).ready(function(){
  $("#jour option").click(function(){
    $.ajax({type:"POST", data: $("#jour").serialize(), url:"planning_ajax.php",
      success: function(data){
        $("#post_planning").html(data);
      },
      error: function(){
        $("#post_planning").html('Une erreur est survenue.');
      }
    });
  });
});

$(".clic-radio").click(function () {
    var id = $(this).attr('id');
    var input_pass = $("#input_event_pass");
    if (id === "match_prive")
        input_pass.show();
    else{
        input_pass.val("");
        input_pass.hide();
    }
});
var heure_debut;

$(".boutton").click(function() {
	heure_debut = $(".creneau_heure_debut", this).attr("value");
	$("#modal_heure_debut").html(heure_debut);
	$('#input_heure_debut').val(heure_debut);
	date = $('#jour').val();
	$('#input_event_date').val(date);
	terrain_id = $(".creneau_terrain_id", this).attr("value");
	$('#input_terrain_id').val(terrain_id);
});

$("#type_event option").click(function(){
	if ($(this).val() == "rencontre"){
		$("#modal_effectif_match").hide();
		$("#modal_effectif_rencontre").show();
	}
	else{
		$("#modal_effectif_match").show();
		$("#modal_effectif_rencontre").hide();
	}
});

$("#cb input").click(function(){
	if ($(this).val() == "1"){
		$("#section-rib_match").show();
	}
	else{
		$("#section-rib_match").hide();
	}
});

$("#select-compte-match option").click(function(){
	if ($(this).val() == "new"){
		$("#new-compte-match").show();
	}
	else{
		$("#new-compte-match").hide();
	}
});

</script>
