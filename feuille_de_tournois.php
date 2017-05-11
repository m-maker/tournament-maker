<?php include ('conf.php');

function recupObjetTournoiByID($id){
	$db = connexionBdd();
	$req_tournoi = $db->prepare("SELECT * FROM tournois WHERE event_id = :id");
	$req_tournoi->bindValue(":id", $id, PDO::PARAM_INT);
	$req_tournoi->execute();
	return $req_tournoi->fetch(PDO::FETCH_OBJ);
}

function recupEquipesCompletes($id_tournoi, $nb_joueur_min){
	$db = connexionBdd();
	$req_equipes = $db->prepare("SELECT * FROM equipes INNER JOIN equipes_tournois ON team_id = et_equipe WHERE et_event_id = :id");
	$req_equipes->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
	$req_equipes->execute();
	$equipes_completes = array();
	while ($equipes = $req_equipes->fetch()) {
		$compte_membres = compter_membres($equipes["team_id"]);
		if ($compte_membres >= $nb_joueur_min)
			$equipes_completes[] = $equipes;
	}
	return $equipes_completes;
}

function recupEquipesIncompletes($id_tournoi, $nb_joueur_min){
	$db = connexionBdd();
	$req_equipes = $db->prepare("SELECT * FROM equipes INNER JOIN equipes_tournois ON team_id = et_equipe WHERE et_event_id = :id");
	$req_equipes->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
	$req_equipes->execute();
	$equipes_incompletes = array();
	while ($equipes = $req_equipes->fetch()) {
		$compte_membres = compter_membres($equipes["team_id"]);
		if ($compte_membres < $nb_joueur_min)
			$equipes_incompletes[] = $equipes;
	}
	return $equipes_incompletes;
}

function compter_membres($id_equipe) {
	$db = connexionBdd();
	$req_nb_membres = $db->prepare("SELECT COUNT(em_id) FROM equipe_membres WHERE em_team_id = :id_team");
	$req_nb_membres->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_nb_membres->execute();
	return $req_nb_membres->fetchColumn();
}

function recupererJoueurs($id_equipe){
	$db = connexionBdd();
	$req_joueurs = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membre_id = em_membre_id INNER JOIN statuts_joueurs ON em_statut_joueur = statut_id WHERE em_team_id = :id_team");
	$req_joueurs->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_joueurs->execute();
	return $req_joueurs->fetchAll();
}

?>

<html>
	<head>
		<?php include ('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/feuille_tournoi.css">
		<title>Tournoi</title>
	</head>

	<body>
	<?php include ('header.php'); ?>

	<?php 
		$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
		$leTournoi = recupObjetTournoiByID($id_tournoi);
		if ($leTournoi->event_prive == 1 && !isset($_POST["mdp"]) || $leTournoi->event_prive == 1 && isset($_POST["mdp"]) && $_POST["mdp"] != $leTournoi->event_pass ){ ?>
				Ce tournoi est privé
				<div class="form-mdp">
					<form method="post">
						<input type="text" placeholder="mot de passe du tournoi" name="mdp" />
						<input type="submit" value="Confirmer" />
					</form>
				</div>
	<?php   
		}else{	
			if (isset($_POST["mdp"]) && $_POST["mdp"] === $leTournoi->event_pass){
				echo "<div class='titre-liste-tournoi'> Bienvenue dans ce tournoi privé </div>";
			} ?>

		<div class="container-fluid">
			<div class="row" id="menu_match">
		    	<div id="btn-mur" class="item-li col-md-4"><a class=" it" data-toggle="tab" href="#Mur">Mur</a></div>
		    	<div id="btn-equipe" class="item-li col-md-4 "><a class="it" data-toggle="tab" href="#mon_equipe">Mon équipe</a></div>
		    	<div id="btn-mon-equipe" class="item-li col-md-4" ><a class=" it" data-toggle="tab" href="#equipes">Équipes</a></div>
		  	</div>

		  	<div id="body_match">
				<div id="contenu_body_match" class="tab-content">

		    		<div id="Mur" class="tab-pane fade in active">
		    			rthr
		    		</div>

		    		<div id="mon_equipe" class="tab-pane fade">
		    			rthr
		    		</div>

		    		<div id="equipes" class="tab-pane fade">
		    			<div class="row categories-equipes">
							<h2>Equipes completes :</h2>
							<?php 

							$equipes_completes = recupEquipesCompletes($id_tournoi, $leTournoi->event_joueurs_min); 
							if (!empty($equipes_completes)){
								foreach ($equipes_completes as $uneEquipe) { ?>
			    				<div class="row equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
			    					<div class="col-md-6"><h1><?php echo $uneEquipe["team_nom"]; ?></h1></div>
			    					<div class="col-md-4"><h1><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</h1></div>
			    					<div class="col-md-2"><button class="btn btn-success">Rejoindre</button> </div>
			    				</div>
			   					<?php 
			   						$joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
			   						$i = 1;
			   					?> 
			   						<div class="row equipe-joueurs" style="display: none;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
			   					<?php
			   						foreach ($joueurs_equipe as $unJoueur) { 
			   							if ($unJoueur["em_membre_paye"] == 1) { $paye = "Payé"; } else { $paye="Non Payé"; } ?>
		    								<div class="col-md-6">
		    									<?php echo $unJoueur["membre_pseudo"]; ?><br />
		    									<?php echo $unJoueur["statut_nom"]; ?>
		    									<span class="statut"><?php echo $paye; ?></span>
		    								</div>
			   					<?php } ?>
			   						</div> 
			   				<?php
								}
			    			}else{
			    				echo "<h3>Il n'y a aucune équipe complète pour l'instant</h3>";
			    			} ?>
		    				
		    			</div>

		    			<div class="row categories-equipes">
							<h2>Equipes incompletes :</h2>
							<?php 

							$equipes_completes = recupEquipesIncompletes($id_tournoi, $leTournoi->event_joueurs_min); 
							if (!empty($equipes_completes)){
								foreach ($equipes_completes as $uneEquipe) { ?>
			    				<div class="row equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
			    					<div class="col-md-6"><h1><?php echo $uneEquipe["team_nom"]; ?></h1></div>
			    					<div class="col-md-4"><h1><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</h1></div>
			    					<div class="col-md-2"><button class="btn btn-success">Rejoindre</button> </div>
			    				</div>
			   					<?php 
			   						$joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
			   						$i = 1;
			   					?> 
			   						<div class="row equipe-joueurs" style="display: none;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
			   					<?php
			   						foreach ($joueurs_equipe as $unJoueur) { 
			   							if ($unJoueur["em_membre_paye"] == 1) { $paye = "Payé"; } else { $paye="Non Payé"; } ?>
		    								<div class="col-md-6">
		    									<?php echo $unJoueur["membre_pseudo"]; ?><br />
		    									<?php echo $unJoueur["statut_nom"]; ?>
		    									<span class="statut"><?php echo $paye; ?></span>
		    								</div>
			   					<?php } ?>
			   						</div> 
			   				<?php
								}
			    			}else{
			    				echo "<h3>Il n'y a aucune équipe incomplète pour l'instant</h3>";
			    			} ?>			
		    			</div>
		    			<hr>
		    			<button class="add-team btn btn-success">Créer mon équipe</button>

		    		</div>

		    	</div>
		    </div>
	    </div>
	    <?php
	    }
	    ?>

	    <script type="text/javascript">
	    	$(".equipe-cont").click(function() {
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

	    	$(".item-li, .item-act").click(function() {
	    		$(".item-li").removeClass("item-act");
	    		$(this).addClass("item-act");
	    	});

	    </script>
	</body>
</html>


