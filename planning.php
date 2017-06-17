<?php
	include ('conf.php');
	$format = 'Y-m-d';
	// $parametres_fonction = $_POST['nom_fonction'];
	$nom_fonction = "afficher_events";
	$parametres_fonction;
	// $parametres_fonction = $_POST['parametres_fonction'];
	$parametres_fonction['lieu_id'] = 7;
	$heure_min = 7.5;
	$heure_max = 24.0;
	$date_min = new DateTime;
	$date_max = clone($date_min);
	$date_max->add( new DateInterval('P6M'));
	var_dump($date_max);

	if ($nom_fonction = "planning_complexe"){
		$req_terrains = $db->prepare('SELECT * FROM terrains WHERE terrain_lieu_id = :lieu_id');
		$req_terrains->execute(array(
			'lieu_id' => $parametres_fonction['lieu_id']
			));
		$res_terrains = $req_terrains->fetchAll();
		$nb_terrain = count($);
		var_dump($res_terrains);
	}
	?>
		<table>
			<?php
				$nom_fonction($date_min, $date_max, $lieu_id, $res_nb_terrains)

				for ($heure = $heure_min; $heure < $heure_max; $heure= $heure + 0.5) {
					?>
						<tr>
							<?php
								$jour = clone($date_min);
								//var_dump($jour);
								//var_dump($date_max);
								if ($jour < $date_max){
									$test_date = 1;
								}
								elseif ($jour > $date_max) {
									$test_date = 0;
								}
								else{
									$test_date = 2;
								}
								//var_dump($test_date);
								entete_complexe($jourmin, $jourmax, $lieu_id);
								while ($jour <= $date_max){
									if ( $jour == $date_min AND $heure == $heure_min){
										?>
											<th></th>
										<?php
									}
									elseif ($heure == $heure_min){
										?>
											<th>
												<?php echo $jour->format("d/m"); ?>
											</th>
										<?php
									}
									elseif ($jour == $date_min) {
										?>
											<th>
											</th>
										<?php
									}
									else{
										?> 
											<td>
												<?php
													//$nom_fonction($parametres_fonction);
													if( $heure == intval($heure)) {
														echo $heure.'H00';
													}
													else{
														echo intval($heure).'H30';
													}
												?>
												<br/>coucou
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
			?>
		</table>

<?php

	function entete_complexe($jourmin, $jourmax, $lieu_id, $tab_terrains){
		$req_format_terrains = $db->prepare('SELECT * FROM formats_terrains');
		$req_format_terrains->execute();
		$format_terrains = $req_format_terrains->fetchAll();
		?>
			<tr class="entete_complexe_jour">
				<?php	
					$jour = clone ($jourmin);
					while ($jour <= $jourmax){
						?>
							<th>
								<?php echo $jour->format("d/m"); ?>
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
								<td>
									<?php echo $terrain['terrain_nom'].'<br>'.$format_terrains[$terrain['terrain_format']];
									?>
								</td>
							<?php
						}
					}
					unset($jour);
				?>
			</tr>
		<?php
	}
?>