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

		<div class="container" id="container" style="padding-top: 0;">

            <div class="row menu-orga espace-bot">
                <div class="col-md-6 center show act" id="show-tournois"><span class="glyphicon glyphicon-list"></span> Les tournois</div>
                <div class="col-md-6 center show" id="show-matchs">Les matchs</div>
            </div>

            <div class="cont" id="tournois">

			<?php

			if (!empty($liste_tournois)){
				foreach ($liste_tournois AS $un_tournoi){
					$heure_debut = format_heure_minute($un_tournoi['event_heure_debut']);
					$heure_fin = format_heure_minute($un_tournoi['event_heure_fin']);
					$duree = format_heure_minute($un_tournoi['event_nb_heure_jeu']);
					?>
					<div class="conteneur-tournoi">
						<a href="feuille_de_tournois.php?tournoi=<?php echo $un_tournoi["event_id"]; ?>">
                            <div class="header-tournoi col-sm-12">
									<?php echo $un_tournoi['event_titre']; ?>
								</div>
                            <div class="row">
                                <div class="logo_tournoi col-lg-2">
                                    <img class="img-responsive img-circle" height="50" src="img/logo-tournois/<?php echo $un_tournoi['event_img']; ?>" alt="Tournoi">
                                </div>
                                <div class="col-lg-3">
                                    <p><span class="glyphicon glyphicon-calendar"></span> <span class="bold"><?php echo $un_tournoi['event_date'];?></span></p>
                                    <p><span class="glyphicon glyphicon-time"></span> <span class="bold"><?php echo $heure_debut.' - '.$heure_fin; ?></span></p>
                                    <p><span class="glyphicon glyphicon-home"></span> Complexe : <span class="bold"><?php echo $un_tournoi['lieu_nom'];?></span></p>
                                </div>
                                <div class="col-lg-3">
                                    <p><span class="glyphicon glyphicon-euro"></span> Prix : <span class="bold"><?php echo $un_tournoi['event_tarif'] + $param->comission; ?> €</span></p>
                                    <p><span class="glyphicon glyphicon-calendar"></span> Durée : <span class="bold"><?php echo $duree; ?></span></p>
                                    <p><span class="glyphicon glyphicon-user"></span> Nombre d'équipes : <span class="bold"><?php echo $un_tournoi['event_nb_equipes']; ?></span></p>
                                </div>
                            </div>
						</a>
					</div>
					<?php
				}
			}
		?>

            </div>

            <div class="cont" id="matchs" style="display: none;">
                lol
            </div>

		</div>

        <?php include('footer.php'); ?>

        <!-- Script pour le menu tournoi / matchs -->
        <script src="js/scripts/menu_tournois_matchs.js" type="text/javascript"></script>

	</body>
<html>