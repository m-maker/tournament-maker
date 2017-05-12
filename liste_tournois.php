<?php
	include "conf.php";
	$dpt = $_GET['dpt'];
	$liste_tournois = liste_tournois($dpt);

	if(isset($_SESSION['id'])){
		$user = $db->prepare('SELECT * FROM membres WHERE id = :membre_id');
		$user->execute(array(
			'id' => $_SESSION['id']
		));
	}

	function liste_tournois($dpt){
		$db = connexionBdd();
		$req_dpt = $db->prepare("SELECT dpt_id FROM departements WHERE dpt_code = :code");
		$req_dpt->bindValue(":code", $dpt, PDO::PARAM_STR);
		$req_dpt->execute();
		$dpt_id = $req_dpt->fetchColumn();
		$req_liste_tournois = $db->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE lieu_dpt_id = :departement_id');
		$req_liste_tournois->execute(array(
			':departement_id' => $dpt_id
			));
		$liste = $req_liste_tournois->fetchAll();
		return $liste;
	}

	function format_heure_minute($heure){
		$hr = new DateTime($heure);
		return $hr->format("H:i").' h';
	}
?>

<html>
	
	<head>
		<?php include ('head.php'); ?>
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
		<title>Tournois de foot en salle</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<div class="titre-liste-tournoi">
			Les tournois dans le <?php echo $dpt; ?>
		</div>
		<div class="container-fluid">

			<?php
			//var_dump($liste_tournois);
			if ($liste_tournois != NULL){
				foreach ($liste_tournois AS $un_tournoi){
					$heure_debut = format_heure_minute($un_tournoi['event_heure_debut']);
					$heure_fin = format_heure_minute($un_tournoi['event_heure_fin']);
					$duree = format_heure_minute($un_tournoi['event_nb_heure_jeu']);
					?>
					<div class="conteneur-tournoi">
						<a href="feuille_de_tournois.php?tournoi=<?php echo $un_tournoi["event_id"]; ?>">
							<div class="row">
								<div class="header-tournoi col-sm-12">
									<?php echo $un_tournoi['event_titre']; ?>
								</div>
							</div>
							<div class="row">
								<div class="body_tournoi">				
									<div class="col-lg-4">
										<div class="logo_tournoi">
											 <img class="img-responsive img-circle" height="50" src='img/logo-tournois/<?php echo $un_tournoi['event_img'];?>' alt="Tournoi">
										</div>
									</div>
									<div class="col-lg-5">
										<h2><?php echo $un_tournoi['event_date'];?></h2>
										<p><?php echo $heure_debut.' - '.$heure_fin; ?></p>
										<h2><?php echo $un_tournoi['lieu_nom'];?></h2>
									</div>
									<div class="col-lg-3">
										<p><span class="glyphicon glyphicon-euro"></span> Prix : <?php echo $un_tournoi['event_tarif']; ?></span></p>
										<p><span class="glyphicon glyphicon-calendar"></span> Durée : <?php echo $duree; ?></p>
										<p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <?php echo $un_tournoi['event_nb_equipes']; ?></p>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php
				}
			}
		?>

		</div>
		<?php include('footer.php'); ?>
	</body>
<html>