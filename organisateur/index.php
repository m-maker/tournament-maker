<?php

include '../conf.php';

if ($_SESSION["membre_orga"] != 1)
	header("Location: ../index.php");
if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

$liste_tournois = liste_tournois_orga($_SESSION["id"]);

?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<?php include('../head.php'); ?>
		<title>Administrer mes tournois</title>
		<link rel="stylesheet" type="text/css" href="css/orga.css">
        <link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
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
        <h1 id="titre_corps">Accueil</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

        <div style="width: 80%; margin: 2% auto; border: 1px solid darkslateblue; border-radius: 5px; padding: 1%;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong style="font-size: 18px;">Bienvenue sur votre espace de gestion, <?php echo $_SESSION["pseudo"]; ?>.</strong> Dans cette section vous pouvez gérer les équipes de vos tournois et vos matchs, voir et rembourser les achats de place des joueurs, modifier / ajouter des tournois ou des matchs...
        </div>

        <div class="center" style="margin-bottom: 1%;">
            <a href="organiser_tournoi.php"><button class="btn btn-success btn-mid"><span class="glyphicon glyphicon-play-circle"></span> Ajouter un tournoi</button></a>
        </div>

		<!-- CONTENU DE LA PAGE -->
		<div class="container-fluid espace-top" style="width: 80%;padding: 0 1% 0;">

				<div class="row espace-bot">

						<!--<h2 class="center"><span class="glyphicon glyphicon-list"></span> Mes tournois</h2>-->

						<?php if (!empty($liste_tournois)){
								foreach ($liste_tournois as $unTournoi) { ?>
                                    <div class="row">
                                    <div class="col-md-10 " style="padding: 0;margin:0; ">
                                        <div class="tournoi-cont" style="padding: 0; margin:0 1% 1%; <?php if($unTournoi["event_tournoi"] == 0){ echo 'background: #B8860B;';} ?>">
										<div class="row infos-tournoi" id="<?php echo $unTournoi[0]; ?>">
											<div class="col-md-6 bold" style="text-decoration: underline;"><?php echo $unTournoi["event_titre"] ?></div>
											<div class="col-md-2"><span class="glyphicon glyphicon-calendar"></span> <?php echo $unTournoi["event_date"]; ?></div>
											<div class="col-md-2"><span class="glyphicon glyphicon-flag"></span> <?php echo compte_equipes($unTournoi[0]); ?> Equipes</div>
											<div class="col-md-2"><span class="glyphicon glyphicon-user"></span>  <?php echo compte_joueurs_tournoi($unTournoi[0]); ?> Inscrits</div>
										</div>
										<div class="row mod-tournoi" id="m-<?php echo $unTournoi[0]; ?>">

                                            <div class="col-md-3">
                                                <a href="mur.php?tournoi=<?php echo $unTournoi[0]; ?>">
                                                    <button class="btn btn-default btn-grand"><span class="glyphicon glyphicon-zoom-in"></span> Voir le mur</button>
                                                </a>
                                            </div>
											<div class="col-md-3">
												<a href="modifier_tournoi.php?id=<?php echo $unTournoi[0]; ?>">
													<button class="btn btn-default btn-grand"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
												</a>
											</div>
											<div class="col-md-3">
												<a href="gestion_equipes.php?tournoi=<?php echo $unTournoi[0]; ?>">
													<button class="btn btn-default btn-grand"><span class="glyphicon glyphicon-cog"></span> Gerer les equipes</button>
												</a>
											</div>
                                            <div class="col-md-3">
                                                <a href="paiements.php?tournoi=<?php echo $unTournoi[0]; ?>">
                                                    <button class="btn btn-default btn-grand"><span class="glyphicon glyphicon-eur"></span> Encaissements</button>
                                                </a>
                                            </div>

										</div>
									</div>
                                    </div>
                                    <div class="col-md-2" >
                                        <a href="suppr_tournoi.php?id=<?php echo $unTournoi[0]; ?>" style="margin: auto;">
                                            <button class="btn btn-default btn-grand" style=" background: slategrey;"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
                                        </a>
                                    </div>
                                    </div>
						<?php } 
							}else{ ?>
								<h4 class="padding center">Vous n'avez crée aucun tournoi !</h4> 
							<?php } ?>

					</div>

        </div>
    </div>
</div>


        <!-- FOOTER -->
		<?php include('footer.php') ?>

        <script src="../js/scripts/menu_tournois_matchs.js" type="text/javascript"></script>

		<script type="text/javascript">

			$(".infos-tournoi").click(function() {
	    		//$(".equipe-joueurs .row").hide().removeClass("act");
	    		var id = $(this).attr("id");
	    		var cont_mod = $("#m-" + id);
	    		console.log(cont_mod);
	    		if (cont_mod.css("display") == "none"){
	    			cont_mod.show();
	    		} else {
	    			cont_mod.hide();
	    		}
	    	});

		</script>

	</body>

</html>