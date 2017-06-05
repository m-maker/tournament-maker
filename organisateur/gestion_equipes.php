<?php

include('../conf.php');

if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

if (isset($_GET["tournoi"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    if ($leTournoi->event_orga_2 != $_SESSION["id"] && $leTournoi->event_orga != $_SESSION["id"])
        header("Location: ../index.php");
}else{
    header("Location: ../index.php");
}
$leTournoi = recupObjetTournoiByID($id_tournoi);

?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title><?php echo $leTournoi->event_titre; ?> - Gerer les equipes</title>
		<link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/gest_team.css">
		<link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <h1 id="titre_corps"><?php echo $leTournoi->event_titre; ?> > Gestion des equipes</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

            
		<!-- HEADER -->
		<?php
			$heure_debut = format_heure_minute($leTournoi->event_heure_debut);
			$heure_fin = format_heure_minute($leTournoi->event_heure_fin);
            $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
            if ($leTournoi->event_prive == 1){$color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";}
            $pay = "<span class='rouge'>Refusé</span>";
            if ($leTournoi->event_paiement == 1){$pay="<span class='vert'>Accepté</span>";}
            $desc = $leTournoi->event_descriptif;
            if ($leTournoi->event_descriptif == NULL || empty($leTournoi->event_descriptif))
                $desc = 'Pas de description.';
            $team = "par équipe";
            if ($leTournoi->event_tarification_equipe == 0){$team="par joueur";}
        $date_tournoi = new DateTime($leTournoi->event_date);
        $date_tournoi = date_lettres($date_tournoi->format("w-d-m-Y"));
		?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container-fluid espace-bot" style="margin: 2% auto;">
            <?php echo "<div class='titre-liste-tournoi'>
            <span class=\"left\"><a href=\"index.php\"> < </a></span>
            " . $leTournoi->event_titre . "<br>
            <p style='font-size: 15px;'>
                <span class=\"glyphicon glyphicon-calendar\"></span> Le <span class=\"bold\">" . $date_tournoi . "</span> de
            <span class=\"bold\">" . $heure_debut . "</span> à <span class=\"bold\">" .$heure_fin . "</span>
            </p>
        </div>"; ?>
			<div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
				<div class="row">

                    <div class="col-lg-4" >
                        <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $leTournoi->lieu_nom;?></span></p>

                        <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($leTournoi->event_id) . ' / ' . $leTournoi->event_nb_equipes; ?></span> équipes inscrites</p>
                        <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>

                    </div>
                    <div class="col-lg-5 espace-top" >
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <?php
                        if (strlen($desc) > 120) {
                            echo substr($desc, 0, 120)  . '...';
                        }else{
                            echo $desc;
                        } ?>
                    </div>
                    <div class="col-lg-3 prix-team">
                        <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $leTournoi->event_tarif; ?> €</span></h1> <?php ECHO $team; ?><br />
                        <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                    </div>

				</div>
			</div>

            <a href="gest_team_form.php?tournoi=<?php echo $leTournoi->event_id; ?>">
                <button class="btn btn-success btn-grand espace-top"><span class="glyphicon glyphicon-play-circle"></span> Créer une nouvelle equipe</button>
            </a>

			<!-- Affichage des équipes -->
			<div class="cont-equipe espace-top">
				<?php 
				$equipes_tournoi = recupEquipesTournoi($id_tournoi);
				if (!empty($equipes_tournoi)){
					foreach ($equipes_tournoi as $uneEquipe) {
					    $nb_joueur_paye = recupNbJoueurPayeEquipe($uneEquipe["team_id"]);
                        $nb_joueur_non_paye = recupNbJoueurPayeEquipe($uneEquipe["team_id"], 0);?>

						<!-- Infos génerales de l'équipe -->
						<div class="equipe-cont">
							<div class="row info-team" id="<?php echo $uneEquipe['team_id']; ?>">
								<div class="col-md-2"><h3 style="margin-top: 5%;"><?php echo $uneEquipe['team_nom']; ?></h3></div>
								<div class="col-md-2"><h3 style="margin-top: 5%;"><?php echo compter_membres($uneEquipe['team_id']); ?> Joueurs</h3></div>
                                <?php if ($leTournoi->event_paiement == 1){ ?>
                                <div class="col-md-2 bold">
                                    <span class="vert"><?php echo $nb_joueur_paye; ?> payés</span><br />
                                    <span class="rouge"> <?php echo $nb_joueur_non_paye ?> non payés</span>
                                </div>
                                <?php } ?>
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

			</div>

		</div>
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
	    			$(this).addClass("active");
	    			cont_joueur.show();
	    		} else {
	    			$(this).removeClass("active");
	    			cont_joueur.hide();
	    		}
	    	});
		</script>

	</body>

</html>