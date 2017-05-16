<?php include ('conf.php');

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

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

function recupMessagesMur($id_tournoi){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM messages_mur INNER JOIN membres ON mur_membre_id = membre_id WHERE mur_tournoi_id = :id ORDER BY mur_date DESC");
	$req->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchAll();
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

function recupMessagesEquipe($id_equipe){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM mur_equipes INNER JOIN membres ON me_membre_id = membre_id WHERE me_equipe_id = :id_equipe ORDER BY me_date DESC;");
	$req->bindValue(":id_equipe", $id_equipe, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchAll();
}

?>

<html>
	<head>
		<?php include ('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/feuille_tournoi.css">
		<link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
		<title>Tournoi</title>
	</head>

	<body>
	<?php include ('header.php'); ?>

	<div class="container-fluid">

	<?php 

		$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
		$leTournoi = recupObjetTournoiByID($id_tournoi);
		if ($leTournoi->event_prive == 1 && !isset($_POST["mdp"]) || $leTournoi->event_prive == 1 && isset($_POST["mdp"]) && $_POST["mdp"] != $leTournoi->event_pass ){ ?>
			<div class="mdp">
				<h3>Ce tournoi est privé !</h3>
				<div class="form-mdp">
					<form method="post">
						<input type="text" placeholder="Saisir le mot de passe" name="mdp" />
						<input type="submit" value="Confirmer" />
					</form>
				</div>
			</div>
	<?php   
		}else{	
			echo "<div class='titre-liste-tournoi'> Bienvenue dans le tournoi : " . $leTournoi->event_titre . "</div>";
			$heure_debut = format_heure_minute($leTournoi->event_heure_debut);
			$heure_fin = format_heure_minute($leTournoi->event_heure_fin);
			$duree = format_heure_minute($leTournoi->event_nb_heure_jeu);
	 	?>

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

			<div class="row" id="menu_match">
		    	<div id="btn-mur" class="item-li col-md-4"><a class="it" data-toggle="tab" href="#mur">Mur</a></div>
		    	<div id="btn-equipe" class="item-li col-md-4 "><a class="it" data-toggle="tab" href="#mon_equipe">Mon équipe</a></div>
		    	<div id="btn-mon-equipe" class="item-li col-md-4" ><a class="it" data-toggle="tab" href="#equipes">Équipes</a></div>
		  	</div>

		  	<div id="body_match" class="espace-bot">
				<div id="contenu_body_match" class="tab-content">

		    		<div id="mur" class="tab-pane fade espace-top">
		    			<form method="post" action="post_msg.php?id=<?php echo $leTournoi->event_id; ?>">
		    				<textarea class="form-control" placeholder="Votre message..." name="message" rows="3"></textarea>
		    				<button class="btn btn-success btn-grand" name="submit">Poster mon message</button>
		    			</form>
		    			<?php $messages = recupMessagesMur($leTournoi->event_id);
		    			foreach ($messages as $unMessage) { ?>
			    			<div class="message-cont espace-top">
			    				<?php echo $unMessage["mur_contenu"]; 
			    				if ($unMessage["membre_id"] == $_SESSION["id"]) { echo '<span class="delete-msg"><a href="delete_msg.php?type=0&id=' . $unMessage["mur_id"] . '&tournoi=' . $leTournoi->event_id . '">X</a></span>'; } ?>
			    				<div class="sign">
			    					Par <span><?php echo $unMessage["membre_pseudo"]; ?></span> le <span><?php echo $unMessage["mur_date"]; ?></span>
			    				</div>
			    			</div>
			    		<?php } ?>
		    		</div>

					<!-- MON EQUIPE ET SES MEMBRES -->
		    		<div id="mon_equipe" class="tab-pane fade">
		    				
			    			<?php 
	    						$mon_equipe = recupEquipeJoueur($_SESSION["id"], $id_tournoi);
	    						if (empty($mon_equipe)){ ?>
	    							<h2 class="err-titre">Vous n'avez pas encore d'équipe</h2>
	    					    <?php }else{ ?>

	    					    <!-- Affichage des paramètres pour le capitaine de l'equipe -->
		    					<?php if (recupStatutJoueur($_SESSION["id"], $mon_equipe["team_id"]) == 1){ ?> 
	    					    <div class="row">
		    						<h2 class="err-titre"><?php echo $mon_equipe["team_nom"]; ?></h2>
	    							<div class="col-md-12 param-team center">
		    							<h3 class="clic-param espace-bot">Paramètres de l'equipe: </h3>
		    							<div>
		    								<form id="form-param-team" method="post" action="param_team.php?id=<?php echo $mon_equipe['team_id']; ?>&tournoi=<?php echo $leTournoi->event_id; ?>">
		    									<input style="width: 50%; margin: auto;" class="form-control" type="text" placeholder="Nom de l'equipe" name="nom-team" value="<?php echo $mon_equipe['team_nom']; ?>"><br />
			    								Etat de l'équipe :
				    							<label class="etat-team espace-left" id="prv">
				    								Privé
				    								<input type="radio" name="etat-team" value="1" <?php if ($mon_equipe["team_prive"] == 1){ echo 'checked'; } ?> />
				    							</label>
				    							<label class="etat-team" id="pub">
				    								Public
				    								<input type="radio" name="etat-team" value="0" <?php if ($mon_equipe["team_prive"] == 0){ echo 'checked'; } ?> />
				    							</label>
				    							<input <?php if ($mon_equipe["team_prive"] == 0){ echo 'style="display: none;"'; }else{ echo 'value="'.$mon_equipe["team_pass"].'"'; } ?> id="mdp-team" class="espace-left" type="text" name="pass-team" placeholder="mot de passe de l'equipe"><br />
				    							<input type="submit" name="submit" class="espace-top btn btn-success moit" value="Enregistrer">
			    							</form>
			    							<button class="btn btn-danger moit espace-bot suppr-team" mod="suppr" id="<?php echo $mon_equipe['team_id']; ?>">Supprimer l'équipe</button>
		    							</div>
	    								Invite tes amis en leur transmettant ce lien : <input style="text-align: center; width: 50%;" type="text" readonly value="<?php echo $param->url_site; ?>invite.php?code_team=<?php echo $mon_equipe["team_code"]; ?>">
	    							</div>
	    						</div>
	    						<?php } ?>

	    						<div class="row">
	    							<div class="col-md-5">
	    							<?php 
	    								$joueurs = recupererJoueurs($mon_equipe["team_id"]);
	    								foreach ($joueurs as $unJoueur) { 
	    									if ($unJoueur["em_membre_paye"] == 1) { $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; } else { $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; }?>
	    									<div class="unJoueur" id="mon-equipe-cont">
	    										<span class="bold"><?php echo $unJoueur["membre_pseudo"]; ?></span><br />
	    										<?php echo $unJoueur["statut_nom"]; ?>
	    										<span class="statut"><?php echo $paye; ?></span>	
	    									</div>
	    								<?php } ?>
	    							</div>


				    				<div class="col-md-7" id="mur-equipe-cont">
				    					<div class="titre-mur-equipe">
				    						<p>Les messages de votre équipe</p>
				    					</div>

				    					<form method="post" action="post_msg_team.php?id=<?php echo $leTournoi->event_id; ?>">
				    						<textarea class="form-control" name="message" placeholder="Entrez votre message..."></textarea>
				    						<button class="btn btn-success btn-grand">Poster mon message</button>
				    					</form>

				    					<?php $messages_equipe = recupMessagesEquipe($mon_equipe["team_id"]);
				    					if (!empty($messages_equipe)){ 
				    						foreach ($messages_equipe as $unMessage) { ?>
						    					<div class="msg-cont espace-bot">
						    						<?php if ($unMessage["membre_id"] == $_SESSION["id"]) { echo '<span class="delete-msg"><a href="delete_msg.php?type=1&id=' . $unMessage["me_id"] . '&tournoi=' . $leTournoi->event_id . '">X</a></span>'; }
						    						echo $unMessage["me_contenu"]; ?>
						    						<div class="sign-msg">
						    							Par <span><?php echo $unMessage["membre_pseudo"]; ?></span> le <span><?php echo $unMessage["me_date"]; ?></span>
						    						</div>
						    					</div>
					    				<?php } 
					    				}else{ ?>
				    						<h4 class="center">Personne n'a posté de message pour le moment.</h4>
				    					<?php } ?>

				    				</div>
			    				</div>
			    			<?php } ?>
		    			
		    		</div>

		    		<div id="equipes" class="tab-pane fade">
		    			<div class="categories-equipes">

		    				<div class="row">
								<div class="col-md-12">
									<h2>Equipes completes :</h2>
								</div>
							</div>

							<?php 
							$equipes_completes = recupEquipesCompletes($id_tournoi, $leTournoi->event_joueurs_min); 
							if (!empty($equipes_completes)){
								foreach ($equipes_completes as $uneEquipe) { ?>
			    				<div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
			    					<div class="row">
			    						<div class="col-md-6"><h1><?php echo $uneEquipe["team_nom"]; ?></h1></div>
			    						<div class="col-md-4"><h1><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</h1></div>
			    						<?php if (recupStatutJoueur($_SESSION["id"], $uneEquipe["team_id"]) == 1){ ?>
			    							<div class="col-md-2"><button style="width: 100%;" mod="suppr" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger">Supprimer</button></div>
			    						<?php }else{
			    							if ($mon_equipe["team_id"] == $uneEquipe["team_id"]){ ?>
			    								<div class="col-md-2"><button style="width: 100%;" mod="leave" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger">Quitter</button></div>
			    							<?php }elseif (empty($mon_equipe) && compter_membres($uneEquipe["team_id"]) <= $leTournoi->event_joueurs_max){ ?>
			    								<div class="col-md-2"><button style="width: 100%;" mod="rej" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-success">Rejoindre</button></div>
			    						<?php }
			    						} ?>
			    					</div>
			    				
			   					<?php 
			   						$joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
			   						$i = 2;
			   					?> 
			   						<div class="equipe-joueurs">
			   							<div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
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
			   						</div>
			   					</div>
			   				<?php
								}
			    			}else{
			    				echo "<h3>Il n'y a aucune équipe complète pour l'instant</h3>";
			    			} ?>	
		    				
		    			</div>

		    			<div class="categories-equipes">
		    				<div class="row">
								<div class="col-md-12">
									<h2>Equipes incompletes :</h2>
								</div>
							</div>

							<?php 
							$equipes_incompletes = recupEquipesIncompletes($id_tournoi, $leTournoi->event_joueurs_min); 
							if (!empty($equipes_incompletes)){
								foreach ($equipes_incompletes as $uneEquipe) { ?>
			    				<div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
			    					<div class="row">
			    						<div class="col-md-6"><h1><?php echo $uneEquipe["team_nom"]; ?></h1></div>
			    						<div class="col-md-4"><h1><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</h1></div>
			    						<?php if (recupStatutJoueur($_SESSION["id"], $uneEquipe["team_id"]) == 1){ ?>
			    							<div class="col-md-2"><button style="width: 100%;" mod="suppr" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger">Supprimer</button></div>
			    						<?php }else{
			    							if ($mon_equipe["team_id"] == $uneEquipe["team_id"]){ ?>
			    								<div class="col-md-2"><button style="width: 100%;" mod="leave" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger">Quitter</button></div>
			    							<?php }elseif (empty($mon_equipe) && compter_membres($uneEquipe["team_id"]) <= $leTournoi->event_joueurs_max){ ?>
			    								<div class="col-md-2"><button style="width: 100%;" mod="rej" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-success">Rejoindre</button></div>
			    						<?php }
			    						} ?>
			    					</div>
			    				
			   					<?php 
			   						$joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
			   						$i = 2;
			   					?> 
			   						<div class="equipe-joueurs">
			   							<div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
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
			   						</div>
			   					</div>
			   				<?php
								}
			    			}else{
			    				echo "<h3>Il n'y a aucune équipe incomplète pour l'instant</h3>";
			    			} ?>			
		    			</div>

		    			<hr>
		    			<?php if(empty($mon_equipe)){ ?>
		    				<button class="add-team btn btn-success" value="<?php echo $leTournoi->event_id; ?>">Créer mon équipe</button>
		    				<form class="espace-top form-equipe" method="post" action="creer_equipe.php?tournoi=<?php echo $leTournoi->event_id; ?>">
		    					<fieldset>
							    	<div class="form-group">
							        	<div class="col-md-8">
							        		<input type="text" class="form-control" id="inputPseudo" name="nom" placeholder="Nom de l'équipe">
							        	</div>
							        	<div class="col-md-4">
							    			<button type="submit" name="submit" class="btn btn-primary btn-grand">Ajouter</button>
							    		</div>        	
								    </div>
							    </fieldset>
		    				</form>
		    			<?php } ?>

		    		</div>

		    	</div>
		    </div>
	    </div>
	    <?php
	    }
	    ?>

	    <script type="text/javascript">
	    	$(".equipe-cont").click(function() {
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

	    	$(".equipe-cont button, .suppr-team").click(function(){
	    		var id = $(this).attr("id");
	    		var mod = $(this).attr("mod");
	    		if (mod != "suppr" || mod == "suppr" && confirm("Etes vous sur de vouloir supprimer votre équipe du tournoi ? Cette action sera définitive !" ))
	    			document.location.replace("action_team.php?mod=" + mod + "&id=" + id);
	    	});

	    	$(".item-li, .item-act").click(function() {
	    		$(".item-li").removeClass("item-act");
	    		$(this).addClass("item-act");
	    	});

	    	$(".etat-team").click(function() {
	    		var id = $(this).attr("id");
	    		var input_pass = $("#mdp-team");
	    		if (id == "pub"){
	    			input_pass.val("");
	    			input_pass.hide();
	    		}else
	    			input_pass.show();
	    	});

	    	$(".add-team").click(function() { 
	    		var form_a_afficher = $(".form-equipe");
	    		if (form_a_afficher.css("display") == "none")
	    			form_a_afficher.show();
	    		else
	    			form_a_afficher.hide();
	    	});

	    	$(".clic-param").click(function() {
	    		var form = $("#mon_equipe #form-param-team");
	    		if (form.css("display") == "none")
	    			form.show();
	    		else
	    			form.hide();
	    	});

	    </script>
	</body>
</html>


