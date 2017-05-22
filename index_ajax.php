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
	?>	
		<button id="btn_dpt" class="btn btn-success" data-toggle="modal" data-target="#myModal">
			<div id="nom_departement" > <?php echo $res_dpt['dpt_nom']; ?>  <b class="caret"></b> </div>
		</button>
	<?php

	//var_dump($liste_tournois);
	if ($liste_tournois != NULL){
		foreach ($liste_tournois AS $un_tournoi){
			$heure_debut = format_heure_minute($un_tournoi['event_heure_debut']);
			$heure_fin = format_heure_minute($un_tournoi['event_heure_fin']);
			?>
				<div class="conteneur-tournoi">
					<div class="header-tournoi">
						<div class="header-tournoi-date">
							<h2 class="date_recap_tournoi"><?php echo $un_tournoi['event_date'];?></h2>
							<p><?php echo $heure_debut.' - '.$heure_fin; ?></p>
						</div>
						<div class="header-tournoi-lieu">
							<h2><?php echo $un_tournoi['lieu_nom'];?></h2>
						</div>
						<div class="logo_tournoi">
							<img class="img-responsive img-circle" src='img/logo-tournois/<?php echo $un_tournoi['event_img'];?>' alt="Tournoi">
						</div>
					</div>
					<div class="corps_tournoi">
						<div class="infos_tournoi col-sm-4">
							<p><span class="glyphicon glyphicon-euro"></span> Prix : <?php echo $un_tournoi['event_tarif']; ?></p>
							<p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <?php echo $un_tournoi['event_nb_equipes']; ?></p>
						</div>
						<div class="descriptif_tournoi col-sm-7">
							<h2><?php echo $un_tournoi['event_titre']; ?></h2>
							<p><?php echo htmlspecialchars($un_tournoi['event_descriptif']); ?></p>
						</div>
					</div>
					<a href="feuille_de_tournois.php?tournoi=<?php echo $un_tournoi["event_id"]; ?>">
					</a>
				</div>
			<?php
		}
	}
?>