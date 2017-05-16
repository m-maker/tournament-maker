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
		<div class="container-fluid">

			<div class="row menu-orga">
				<div class="col-md-4 center show act" id="show-info">Mes Informations</div>
				<div class="col-md-4 center show tournois" id="show-tournois">Mes tournois</div>
				<div class="col-md-4 center show profil" id="show-profil">Mes matchs</div>
			</div>

			<div class="cont" id="infos">
				<div class="row">
					<div class="col-md-6 mid-cont">
						<h2 class="center">Mes informations</h2>
					</div>
				</div>
			</div>

			<div class="cont" id="tournois">
				<div class="row">
					<div class="col-md-12 mid-cont">

						<h2 class="center">Mes tournois</h2>

						<?php if (!empty($liste_tournois)){
								foreach ($liste_tournois as $unTournoi) { ?>
									<div class="tournoi-cont">
										<div class="row infos-tournoi" id="<?php echo $unTournoi["event_id"]; ?>">
											<div class="col-md-2"><img src="<?php echo $unTournoi["event_img"]; ?>" width=""></div>
											<div class="col-md-4"><?php echo $unTournoi["event_titre"] ?></div>
											<div class="col-md-3"><?php echo $unTournoi["event_date"]; ?></div>
											<div class="col-md-3">0 Inscrits</div>
										</div>
										<div class="row mod-tournoi" id="m-<?php echo $unTournoi["event_id"]; ?>">
											<div class="col-md-3"><a target="_blank" href="feuille_de_tournois.php?tournoi=<?php echo $unTournoi['event_id']; ?>"><button class="btn btn-primary btn-grand">Voir</button></a></div>
											<div class="col-md-3"><a href="modifier_tournoi.php?id=<?php echo $unTournoi['event_id']; ?>"><button class="btn btn-primary btn-grand">Modifier</button></a></div>
											<div class="col-md-3"><button class="btn btn-primary btn-grand">Gerer les equipes</button></div>
											<div class="col-md-3"><button class="btn btn-danger btn-grand">Supprimer</button></div>
										</div>
									</div>
						<?php } 
							}else{ ?>
								<h4 class="padding center">Vous n'avez crée aucun tournoi !</h4> 
							<?php } ?>

							<div class="center espace-top">
								<a href="organiser_tournoi.php"><button class="btn btn-success btn-mid">Ajouter un tournoi</button></a>
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