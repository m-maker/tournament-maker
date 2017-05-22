<?php
	include "conf.php";
	$dpt = $_GET['dpt'];
	$liste_tournois = liste_tournois($dpt);

	if(isset($_SESSION['id'])){
		$user = $db->prepare('SELECT * FROM membres WHERE membre_id = :membre_id');
		$user->execute(array(
			'membre_id' => $_SESSION['id']
		));
	}

?>

<html>
	
	<head>
		<?php include ('head.php'); ?>
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/liste_tournois.css">

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
								<div class="header-tournoi col-sm-12">
									<?php echo $un_tournoi['event_titre']; ?>
								</div>
							<div class="row">
								<div class="logo_tournoi col-lg-4">
									<img class="img-responsive img-circle" height="50" src='img/logo-tournois/<?php echo $un_tournoi['event_img'];?>' alt="Tournoi">
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