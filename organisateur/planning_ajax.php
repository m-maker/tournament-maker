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
	<html>
	
	<head>
		<?php include('head.php'); ?>
		<?php include('../head.php'); ?>
		<title>Planning</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
  		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">

  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  		<script src="../bootstrap/js/bootstrap.js"></script>
  	</head>
  	<body>
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
		</body>
		</html>
