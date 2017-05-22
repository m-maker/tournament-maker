<?php
	include "conf.php";

	$dpt = $_POST['dpt'];
	$liste_tournois = liste_tournois($dpt);

	global $res_dpt_from_liste_tournois;

	if(isset($_SESSION['id'])){
		$user = $db->prepare('SELECT * FROM membres WHERE membre_id = :membre_id');
		$user->execute(array(
			'membre_id' => $_SESSION['id']
		));
	}

	$req_dpt = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt->execute(array(
		'dpt_code' => $dpt
		));
	$res_dpt = $req_dpt->fetch();

	$liste_complexes = liste_lieux($res_dpt['dpt_id']);

	foreach ($liste_complexes as $key => $value) {
		//var_dump($key);
		//var_dump($value);
		$req_nb_events = $db->prepare('SELECT COUNT(event_id) FROM tournois WHERE event_lieu = :event_lieu');
		$req_nb_events->execute(array(
			'event_lieu' => $value['lieu_id']
			));
		$res_nb_events = $req_nb_events->fetchColumn();
		$tab_complexes_events = array( $value['lieu_id'] => $res_nb_events);
		//var_dump($tab_complexes_events);
		$req_nb_events->closeCursor();
	}
	arsort($tab_complexes_events, SORT_NUMERIC);
	//var_dump($tab_complexes_events); 
?>		
		<button id="btn_dpt" class="btn btn-success" data-toggle="modal" data-target="#myModal">
			<div id="nom_departement" > <?php echo $res_dpt['dpt_nom']; ?>  <b class="caret"></b> </div>
		</button>

<nav id="liste_complexes" class="navbar navbar-default">
 	<div class="container-fluid">
 		<div id="menu_liste_complexes" class="nav navbar-nav">
			<?php 
				foreach ($tab_complexes_events as $lieu_id => $nb_events) {
					$lieu = recupLieuById($lieu_id);
					?>
	      				<a class="onglet_complexe" data-toggle="tab" href="#<?php echo $lieu['lieu_nom'];?>"><?php echo $lieu['lieu_nom'].' ('.$nb_events.') '; ?></a>
	      			<?php
	      		}
	      	?>
	    </div>
	</div>
</nav>

<div id="liste_events" class="tab-content">
	<?php 
		foreach ($tab_complexes_events as $lieu_id => $nb_events) {
			$lieu = recupLieuById($lieu_id);
			$liste_events = liste_tournois_complexe($lieu_id);
			//var_dump($lieu_id);
			//var_dump($liste_events);
			?>
    			<div id="<?php echo $lieu['lieu_id'];?>" class="tab-pane fade in active">
    			<?php
    				foreach ($liste_events as $key => $event) {
						$heure_debut = format_heure_minute($event['event_heure_debut']);
						$heure_fin = format_heure_minute($event['event_heure_fin']);
						$lieu = recupLieuById($lieu_id);
							?>
								<div class="conteneur-tournoi">
									<div class="header-tournoi">
										<div class="header-tournoi-date">
											<h2 class="date_recap_tournoi"><?php echo $event['event_date'];?></h2>
											<p><?php echo $heure_debut.' - '.$heure_fin; ?></p>
										</div>
										<div class="header-tournoi-lieu">
											<h2><?php echo $lieu['lieu_nom'];?></h2>
										</div>
										<div class="logo_tournoi">
											<img class="img-responsive img-circle" src='img/logo-tournois/<?php echo $event['event_img'];?>' alt="Tournoi">
										</div>
									</div>
									<div class="corps_tournoi">
										<div class="infos_tournoi col-sm-4">
											<p><span class="glyphicon glyphicon-euro"></span> Prix : <?php echo $event['event_tarif']; ?></p>
											<p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <?php echo $event['event_nb_equipes']; ?></p>
										</div>
										<div class="descriptif_tournoi col-sm-7">
											<h2><?php echo $event['event_titre']; ?></h2>
											<p><?php echo htmlspecialchars($event['event_descriptif']); ?></p>
										</div>
									</div>
									<a href="feuille_de_tournois.php?tournoi=<?php echo $event["event_id"]; ?>">
									</a>
								</div>
							<?php
    				}
    			?>
    			</div>
    		<?php
    	}
    ?>
</div>
			