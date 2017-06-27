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
		//header('locaton:index.php');
		$_SESSION['gerant_lieu_id'] = 1;
	}
	elseif (isset($_POST['gerant_lieu_id'])){
		$_SESSION['gerant_lieu_id'] = $_POST['gerant_lieu_id'];
	}
		
	
	if ($nom_fonction = "planning_complexe"){

		$parametres_fonction['lieu_id'] = $_SESSION['gerant_lieu_id'];
		$heure_min = 10;
		$heure_max = 23.0;
		if (isset($_POST['jour'])){
			$date_min = DateTime::createFromFormat('Y-m-j', $_POST['jour']);
		}
		else{
			$date_min = new DateTime;
		}
			
		$date_max = clone($date_min);
		$date_max->add( new DateInterval('P0D'));

		$req_terrains = $db->prepare('SELECT * FROM terrains WHERE terrain_lieu_id = :lieu_id');
		$req_terrains->execute(array(
			'lieu_id' => $parametres_fonction['lieu_id']
			));
		$res_terrains = $req_terrains->fetchAll();
		$nb_terrain = count($res_terrains);

		foreach ($res_terrains as $key => $val){
			$req_liste_creneaux = $db->prepare('SELECT * FROM creneaux WHERE creneau_terrain_id = :terrain_id');
			$req_liste_creneaux->execute(array(
				'terrain_id' => $val['id']
				));
			$res_liste_creneaux = $req_liste_creneaux->fetchAll();
			$res_terrains[$key]['creneaux'] = $res_liste_creneaux;
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
		foreach ($res_terrains as $kk) {
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
											$datetime_string = $jour->format('Y-m-j').' '.intval($heure).':'.$minutes.':00';
											$date_case = DateTime::createFromFormat('Y-m-j H:i:s', $datetime_string);
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
			</div><div class="modal fade" id="modal_form_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h2 class="modal-title" id="myModalLabel"></h2>
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
												<option value="$modal_heure_fin"> <?php echo $modal_heure_fin; ?> </option>
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
			 	if ($value['creneau_datetime'] == $date_heure){
			 		if ($value['creneau_statut_id'] == 1) {
						?>
							<td style="margin: 0px; padding: 0px; ">
								<button  style="margin: 0px; padding: 0px; " class="boutton" data-toggle="popover" data-trigger="focus"  title="Participants" container='body' data-html="true" data-content="
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
						?>
							<td rowspan="<?php echo $nb_demi_heure; ?>">
								<button  class="boutton" data-toggle="popover" data-trigger="focus"  title="Participants" container='body' data-html="true" data-content="
								 		<?php //planning_popover(); ?>">
								créneau réservé <?php echo $nb_demi_heure; ?>
								</button>
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
						<td class="creneau_indispo" style="margin: 0px; padding: 0px; ">
							<button type="button" style="margin: 0px; padding: 0px; " class="boutton" data-toggle="modal" data-target="#modal_form_1">				 		 
								<?php 
									$ex = explode(" ", $date_heure); 
									$ex2 = explode(":", $ex[1]);
									echo $ex2[0].':'.$ex2[1];
									?>
							</button>
						</td>
					<?php
				}
			}
		}
	}
?>	


      <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
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
	heure_debut = $(this).text();
	$('#modal_heure_debut').html(heure_debut);
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
</script>