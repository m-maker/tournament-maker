<?php 

include('conf.php'); 

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["tournoi"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$req = $db->prepare("SELECT event_orga FROM tournois WHERE event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	$id_orga = $req->fetchColumn();
	if ($_SESSION["id"] != $id_orga)
		header("Location: index.php");
}else{
	header("Location: index.php");
}
$leTournoi = recupObjetTournoiByID($id_tournoi);

?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title><?php echo $leTournoi->event_titre; ?> - Gerer les equipes</title>
		<link rel="stylesheet" type="text/css" href="css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/gest_team.css">
		<link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
	</head>

	<body>

		<!-- HEADER -->
		<?php
			include('header.php'); 
			$heure_debut = format_heure_minute($leTournoi->event_heure_debut);
			$heure_fin = format_heure_minute($leTournoi->event_heure_fin);
			$duree = format_heure_minute($leTournoi->event_nb_heure_jeu);
		?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container" style="margin: 2% auto;background: rgba(61,86,110,0.5); border-right: 1px solid grey; border-left: 1px solid grey; width: 80%; padding:1%;">
		<h2 class="titre center"><span class="left"><a href="gestion_orga.php"> < </a></span> Gerer les équipes</h2>
			<div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
				<div class="row">

					<div class="col-lg-4 center" style="padding: 1% 2% 0;">
						<div class="logo_tournoi">
							 <img class="img-responsive img-circle" height="50" src='img/logo-tournois/<?php echo $leTournoi->event_img;?>' alt="Tournoi">
						</div>
					</div>
					<div class="col-lg-5">
						<p><span class="glyphicon glyphicon-calendar"></span> <?php echo $leTournoi->event_date;?></p>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo $heure_debut.' - '.$heure_fin; ?></p>
						<p><span class="glyphicon glyphicon-home"></span> <?php echo $leTournoi->lieu_nom;?></p>
					</div>
					<div class="col-lg-3">
						<p><span class="glyphicon glyphicon-euro"></span> Prix : <?php echo $leTournoi->event_tarif; ?></span></p>
						<p><span class="glyphicon glyphicon-calendar"></span> Durée : <?php echo $duree; ?></p>
						<p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <?php echo $leTournoi->event_nb_equipes; ?></p>
					</div>

				</div>
			</div>

			<!-- Affichage des équipes -->
			<div class="cont-equipe espace-top">
				<?php 
				$equipes_tournoi = recupEquipesTournoi($id_tournoi);
				if (!empty($equipes_tournoi)){
					foreach ($equipes_tournoi as $uneEquipe) { ?>

						<!-- Infos génerales de l'équipe -->
						<div class="equipe-cont">
							<div class="row info-team" id="<?php echo $uneEquipe['team_id']; ?>">
								<div class="col-md-3"><?php echo $uneEquipe['team_nom']; ?></div>
								<div class="col-md-3"><?php echo compter_membres($uneEquipe['team_id']); ?> Joueurs</div>
								<div class="col-md-6">
									<a href="gest_joueur_form.php?tournoi=<?php echo $leTournoi->event_id; ?>&team=<?php echo $uneEquipe['team_id']; ?>"><button class="btn btn-success" style="width: 33%;"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter un joueur</button></a>
									<a href="gest_team_form.php?tournoi=<?php echo $leTournoi->event_id; ?>&id=<?php echo $uneEquipe['team_id']; ?>">
										<button class="btn btn-primary" style="width: 32%;"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
									</a>
									<a href="suppr_team.php?tournoi=<?php echo $leTournoi->event_id; ?>&team=<?php echo $uneEquipe['team_id']; ?>">
										<button class="btn btn-danger btn-mid" style="width: 32%;"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
									</a>
								</div>
							</div>
						</div>

						<div class="row joueurs-team" style="margin:0;" id="e-<?php echo $uneEquipe['team_id']; ?>">
						<!-- Joueurs de l'équipe -->
						<?php $joueurs_team = recupererJoueurs($uneEquipe['team_id']);
						if (!empty($joueurs_team)) { 
							foreach ($joueurs_team as $unJoueur) {
								if ($unJoueur["em_membre_paye"] == 1) { $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; } else { $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; }?>
									<div class="col-md-6 un-joueur">
										<div class="col-md-3">
											<?php echo $unJoueur["membre_pseudo"]; ?><br />
											<?php echo $unJoueur["statut_nom"]; ?>
										</div>
										<div class="col-md-3">
											<?php echo $paye; ?>
										</div>
										<div class="col-md-3">
											<a href="gest_joueur_form.php?tournoi=<?php echo $leTournoi->event_id; ?>&team=<?php echo $uneEquipe['team_id']; ?>&id=<?php echo $unJoueur['membre_id']; ?>">
												<button class="btn btn-primary btn-grand"><span class="glyphicon glyphicon-wrench"></span> Modifier</button>
											</a>
										</div>
										<div class="col-md-3">
											<a href="exclure.php?tournoi=<?php echo $leTournoi->event_id; ?>&team=<?php echo $uneEquipe['team_id']; ?>&id=<?php echo $unJoueur['membre_id']; ?>">
												<button class="btn btn-warning btn-grand"><span class="glyphicon glyphicon-remove"></span> Exclure</button>
											</a>
										</div>
									</div>

							<?php } 
						}else{ ?>
							<h3 class="center">Il n'y a pas encore de joueurs dans cette équipe</h3>
						<?php } ?>
					</div>

					<?php }
				}else{ ?>
					<div class="equipe-cont"><h2 style="margin: 0;" class="center">Il n'y a pas d'equipes pour ce tournoi pour l'instant</h2></div>
				<?php } ?>

				<a href="gest_team_form.php?tournoi=<?php echo $leTournoi->event_id; ?>">
					<button class="btn btn-success btn-grand espace-top"><span class="glyphicon glyphicon-play-circle"></span> Créer une nouvelle equipe</button>
				</a>
			</div>

		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script type="text/javascript">
			$(".info-team").click(function() {
	    		//$(".equipe-joueurs .row").hide().removeClass("act");
	    		var id = $(this).attr("id");
	    		var cont_joueur = $("#e-" + id);
	    		if (cont_joueur.css("display") == "none"){
	    			$(this).addClass("act");
	    			cont_joueur.show();
	    		} else {
	    			$(this).removeClass("act");
	    			cont_joueur.hide();
	    		}
	    	});
		</script>

	</body>

</html>