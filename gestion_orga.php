<?php 

include('conf.php'); 
//if ($_SESSION["orga"] != 1)
	//header("Location: index.php");
if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

$liste_tournois = liste_tournois_orga($_SESSION["id"]);
//var_dump($liste_tournois);
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Administrer mes tournois</title>
		<link rel="stylesheet" type="text/css" href="css/orga.css">
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container" id="container" style="padding-top: 0;">

			<div class="row menu-orga">
				<div class="col-md-4 center show" id="show-info">Mes Informations</div>
				<div class="col-md-4 center show act" id="show-tournois"><span class="glyphicon glyphicon-list"></span> Mes tournois</div>
				<div class="col-md-4 center show profil disabled" id="show-profil">Mes matchs</div>
			</div>

			<div class="cont" id="infos">
				<div class="row">
					<div class="col-md-6 mid-cont">
						<h2 class="center">Mes informations</h2>
					</div>
				</div>
			</div>

			<div class="cont visible" id="tournois">
				<div class="row">
					<div class="col-md-12 mid-cont">

						<h2 class="center"><span class="glyphicon glyphicon-list"></span> Mes tournois</h2>

						<?php if (!empty($liste_tournois)){
								foreach ($liste_tournois as $unTournoi) { ?>
									<div class="tournoi-cont">
										<div class="row infos-tournoi" id="<?php echo $unTournoi["event_id"]; ?>">
											<div class="col-md-1"><img class="img-responsive img-circle" src="img/logo-tournois/<?php echo $unTournoi["event_img"]; ?>" width=""></div>
											<div class="col-md-4"><?php echo $unTournoi["event_titre"] ?></div>
											<div class="col-md-3"><?php echo $unTournoi["event_date"]; ?></div>
											<div class="col-md-2"><?php echo compte_equipes($unTournoi['event_id']); ?> Equipes</div>
											<div class="col-md-2"><?php echo compte_joueurs_tournoi($unTournoi['event_id']); ?> Inscrits</div>
										</div>
										<div class="row mod-tournoi" id="m-<?php echo $unTournoi["event_id"]; ?>">
											<div class="col-md-3">
												<a target="_blank" href="feuille_de_tournois.php?tournoi=<?php echo $unTournoi['event_id']; ?>">
													<button class="btn btn-primary btn-grand"><span class="glyphicon glyphicon-zoom-in"></span> Voir</button>
												</a>
											</div>
											<div class="col-md-3">
												<a href="modifier_tournoi.php?id=<?php echo $unTournoi['event_id']; ?>">
													<button class="btn btn-primary btn-grand"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
												</a>
											</div>
											<div class="col-md-3">
												<a href="gestion_equipes.php?tournoi=<?php echo $unTournoi['event_id']; ?>">
													<button class="btn btn-primary btn-grand"><span class="glyphicon glyphicon-cog"></span> Gerer les equipes</button>
												</a>
											</div>
											<div class="col-md-3">
												<a href="suppr_tournoi.php?id=<?php echo $unTournoi['event_id']; ?>">
													<button class="btn btn-danger btn-grand"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
												</a>
											</div>
										</div>
									</div>
						<?php } 
							}else{ ?>
								<h4 class="padding center">Vous n'avez crée aucun tournoi !</h4> 
							<?php } ?>

							<div class="center espace-top">
								<a href="organiser_tournoi.php"><button class="btn btn-success btn-mid"><span class="glyphicon glyphicon-play-circle"></span> Ajouter un tournoi</button></a>
							</div>

					</div>
				</div>
			</div>

			<div class="cont" id="infos">
				
			</div>

		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script type="text/javascript">
			$(".show").click(function() {
				$(".show").removeClass("act");
				$(this).addClass("act");
				$(".cont").hide();
				var id = $(this).attr("id");
				if (id == "show-info")
					$("#infos").show();
				else if (id == "show-tournois")
					$("#tournois").show();
			});

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