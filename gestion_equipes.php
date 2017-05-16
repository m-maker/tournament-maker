<?php 

include('conf.php'); 
//if ($_SESSION["orga"] != 1)
	//header("Location: index.php");
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
//var_dump($liste_tournois);
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Administrer mes tournois</title>
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
		<div class="container-fluid">

			<div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
				<div class="row">

					<div class="col-lg-4 center">
						<div class="logo_tournoi">
							 <img class="img-responsive img-circle" height="50" src='img/logo-tournois/<?php echo $leTournoi->event_img;?>' alt="Tournoi">
						</div>
					</div>
					<div class="col-lg-5">
						<h2><?php echo $leTournoi->event_date;?></h2>
						<p><?php echo $heure_debut.' - '.$heure_fin; ?></p>
						<h2><?php echo $leTournoi->lieu_nom;?></h2>
					</div>
					<div class="col-lg-3">
						<p><span class="glyphicon glyphicon-euro"></span> Prix : <?php echo $leTournoi->event_tarif; ?></span></p>
						<p><span class="glyphicon glyphicon-calendar"></span> Durée : <?php echo $duree; ?></p>
						<p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <?php echo $leTournoi->event_nb_equipes; ?></p>
					</div>

				</div>
			</div>

			<div class="cont-equipe espace-top espace-bot">
				<?php 
				$equipes_tournoi = recupEquipesTournoi($id_tournoi);
				if (!empty($equipes_tournoi)){
					foreach ($equipes_tournoi as $uneEquipe) { ?>
						<div class="equipe-cont">
							<div class="row">
								<div class="col-md-3"><?php echo $uneEquipe['team_nom']; ?></div>
								<div class="col-md-4"><?php echo compter_membres($uneEquipe['team_id']); ?> Joueurs</div>
								<div class="col-md-5">
									<a href="gest_team_form.php?tournoi=<?php echo $leTournoi->event_id; ?>&id=<?php echo $uneEquipe['team_id']; ?>">
										<button class="btn btn-primary" style="width: 48%;"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
									</a>
									<a href="suppr_team.php">
										<button class="btn btn-danger btn-mid" tyle="width: 48%;"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
									</a>
								</div>
							</div>
						</div>
					<?php }
				}else{ ?>
					<div class="equipe-cont"><h2 style="margin: 0;" class="center">Il n'y a pas d'equipes pour ce tournoi pour l'instant</h2></div>
				<?php } ?>

				<a href="gest_team_form.php?tournoi=<?php echo $leTournoi->event_id; ?>"><button class="btn btn-success btn-grand espace-top"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter une equipe</button></a>
			</div>

		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

	</body>

</html>