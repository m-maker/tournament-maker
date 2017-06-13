<?php
	include ('conf.php');
	$format = 'Y-m-d';
	$nom_fonction = "afficher_events";
	$parametres_fonction;
	$parametres_fonction['lieu_id'] = 7;
	$heure_min = 7.5;
	$heure_max = 24.0;
	$date_min = new DateTime;
	// On enlève un jour et un heure au valeur min, afin de générer les entête du tableau
	//enlever un jour idem pour l'heure

	//définition de la date_max;
	if ($nom_fonction == "afficher_events"){
		$req_date_max = $db->prepare('SELECT MAX(event_date) FROM tournois WHERE event_lieu = :event_lieu');
		$req_date_max->execute(array(
			'event_lieu' => $parametres_fonction['lieu_id']
			));
		$res_date_max = $req_date_max->fetch();
		$date_max = DateTime::createFromFormat($format, $res_date_max[0]);
	}
	?>
		<table>
			<?php
				$date_min->sub( new DateInterval('P1D'));

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