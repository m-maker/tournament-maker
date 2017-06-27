
<?php
	include ('conf.php');
	$format = 'Y-m-d';
	// $parametres_fonction = $_POST['nom_fonction'];
	$nom_fonction = "planning_complexe";
	$parametres_fonction;
	// $parametres_fonction = $_POST['parametres_fonction'];
	$parametres_fonction['lieu_id'] = 1;
	$heure_min = 7.5;
	$heure_max = 24.0;
	$date_min = new DateTime;
	$date_max = clone($date_min);
	$date_max->add( new DateInterval('P0D'));

	if ($nom_fonction = "planning_complexe"){

		$req_terrains = $db->prepare('SELECT * FROM terrains WHERE terrain_lieu_id = :lieu_id');
		$req_terrains->execute(array(
			'lieu_id' => $parametres_fonction['lieu_id']
			));
		$res_terrains = $req_terrains->fetchAll();
		$nb_terrain = count($res_terrains);

		
		foreach ($res_terrains as $key => $val) {
			$req_liste_creneaux = $db->prepare('SELECT * FROM creneaux WHERE creneau_terrain_id = :terrain_id');
			$req_liste_creneaux->execute(array(
			'terrain_id' => $val['id']
				));
			$res_liste_creneaux = $req_liste_creneaux->fetchAll();
			$res_terrains[$key]['creneaux'] = $res_liste_creneaux;
			}
	}
	?>

<!DOCTYPE html>
<html>
<head>
	<?php include('head.php'); ?>
	<link rel="stylesheet" type="text/css" href="css/planning.css">

  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

  		<script type="text/javascript" src="js/datepicker.js"></script>
	<div class="tableau"> 
	<div class="has-feedback">
		<label for="datepicker">Date <i class="glyphicon glyphicon-calendar"></i></label>
	   	<input type="text" name="date_planning" id="datepicker">
	</div>
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
	</body>
</html>
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
		//	1 = indisponible / 2 = réservé
		
		foreach ($liste_terrains as $terrain => $val) {
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
					}
				}
				else{
					?>
						<td class="creneau_indispo" style="margin: 0px; padding: 0px; ">
							<button type="button" style="margin: 0px; padding: 0px; " class="boutton" data-toggle="popover" data-trigger="focus"  title="Participants" container='body' data-html="true" data-content="<?php //planning_popover(); ?>">				 		
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
