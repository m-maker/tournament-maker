<?php include ('conf.php');

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

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

function recupMessagesEquipe($id_equipe)
{
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM mur_equipes INNER JOIN membres ON me_membre_id = membre_id WHERE me_equipe_id = :id_equipe ORDER BY me_date DESC;");
    $req->bindValue(":id_equipe", $id_equipe, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
}

$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
$leTournoi = recupObjetTournoiByID($id_tournoi);

$mdp = false;
if ($leTournoi->event_prive == 1 && !isset($_POST["mdp"]) || $leTournoi->event_prive == 1 && isset($_POST["mdp"]) && $_POST["mdp"] != $leTournoi->event_pass)
    $mdp = true;
if (!empty(recupEquipeJoueur($_SESSION['id'], $id_tournoi)))
    $mdp = false;
$mon_equipe = recupEquipeJoueur($_SESSION["id"], $id_tournoi);
var_dump($mon_equipe);
$liste_joueurs_equipe = recupererJoueurs($mon_equipe['team_id']);
?>

<html>
	<head>
		<?php include ('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/feuille_tournoi.css">
		<link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
		<title><?php echo $leTournoi->event_titre; ?></title>
	</head>

	<body>
	<?php include ('header.php'); ?>

	<div class="container" id="container" <?php if ($mdp){echo 'style="margin: 7% auto;"';} ?>>

	<?php if ($mdp){ ?>
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

			echo "<div class='titre-liste-tournoi'>
            " . $leTournoi->event_titre . "<br>
            <p style='font-size: 15px;'>
                <span class=\"glyphicon glyphicon-calendar\"></span> Le <span class=\"bold\">" . $leTournoi->event_date . "</span> de 
                <span class=\"bold\">" . $heure_debut . "</span> à <span class=\"bold\">" .$heure_fin . "</span>
            </p>
            </div>";

	 	?>

			<div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
				<div class="row">

                    <div class="col-lg-4" style="text-align: left;">
                        <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $leTournoi->lieu_nom;?></span></p>
                        <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                        <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($leTournoi->event_id) . ' / ' . $leTournoi->event_nb_equipes; ?></span> équipes inscrites</p>
                    </div>
                    <div class="col-lg-5 espace-top" style="text-align: left;">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <?php
                        if (strlen($desc) > 120) {
                            echo substr($desc, 0, 120)  . '...';
                        }else{
                            echo $desc;
                        } ?>
                    </div>
                    <div class="col-lg-3 prix-team">
                        <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $leTournoi->event_tarif + $param->comission; ?> €</span></h1> <?php ECHO $team; ?><br />
                        <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                    </div>

				</div>
			</div>

        <div class="menu-orga">
            <div class="col-md-4 center show" id="show-mur"><span class="glyphicon glyphicon-list"></span> Mur du tournoi</div>
            <div class="col-md-4 center show" id="show-mon-equipe"><span class="glyphicon glyphicon-flag"></span> Mon Equipe</div>
            <div class="col-md-4 center show acti" id="show-equipes"><span class="glyphicon glyphicon-user"></span> Les équipes</div>
        </div>

		  	<div id="body_match" class="espace-bot container-fluid">
				<div id="contenu_body_match" class="tab-content">

		    		<div id="mur" style="display: none;" class="cont espace-top">
		    			<form method="post" action="post_msg.php?id=<?php echo $leTournoi->event_id; ?>">
		    				<textarea class="form-control" placeholder="Votre message..." name="message" rows="3" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;"></textarea>
		    				<button class="btn btn-success btn-grand" style="border-top-left-radius: 0; border-top-right-radius: 0;" name="submit">Poster mon message</button>
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
		    		<div id="mon_equipe" class="cont" style="display: none;">
		    			<div>
		    				<div class="col-sm-4">
		    					<!-- pour la marge -->
		    				</div>
			    			<?php 
	    						if (empty($mon_equipe)){ 
	    							?>
	    								<h2 class="err-titre col-sm-4" style="margin-top: 0;margin-bottom: 1%; font-weight: normal;">Vous n'avez pas encore d'équipe</h2>
	    					    	<?php 
	    					    }
	    					    else { 
	    					    	?>
                                    <h2 class="err-titre col-sm-4" style="margin-top: 0;margin-bottom: 1%; font-weight: normal;">Equipe <?php echo $mon_equipe["team_nom"]; ?></h2>
                                    <?php 
                                    if (recupStatutJoueur($_SESSION["id"], $mon_equipe["team_id"]) == 1){
                                    	 ?>
                                            <div class="param-team center col-sm-4">
                                                <h3 class="clic-param espace-bot">Paramètres de l'equipe <span class="glyphicon glyphicon-menu-down right"></span></h3>
                                       	        <div>
                                                    <form id="form-param-team" method="post" action="param_team.php?id=<?php echo $mon_equipe['team_id']; ?>&tournoi=<?php echo $leTournoi->event_id; ?>">
                                                        <input style=" margin: auto;" class="form-control" type="text" placeholder="Nom de l'equipe" name="nom-team" value="<?php echo $mon_equipe['team_nom']; ?>"><br />
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
                                                            <input type="submit" name="submit" class="espace-top btn btn-success btn-grand" value="Enregistrer">
                                                    </form>
                                                    <button class="btn btn-danger btn-grand espace-bot suppr-team" mod="suppr" id="<?php echo $mon_equipe['team_id']; ?>">Supprimer l'équipe</button>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    ?>
                    	</div>
                    	<div id="liste_joueurs_equipe" class="center container-fluid">
                    		<div class="row">
                    			<h2 class="center">Les joueurs sélectionnés</h2>
                    				<div class="liste_joueur">
                    				<?php 
                    					foreach ($liste_joueurs_equipe as $key => $value) {
                    						if ($value['em_statut_joueur'] == 1 OR $value['em_statut_joueur'] == 3){
                    							if ($value['em_membre_id'] == $_SESSION['id']){
                    								?>
                    									<div class="joueur_user">
                    										<div class="">
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>";
                    											?>
                           									</div>
                           									<div class="">
                           										<a href="pay/creer_utilisateur.php?tournoi=<?php echo $leTournoi->event_id; ?>">
                                        							<button " class="btn btn-success btn-xs">Me desinscrire <br/>(a faire) (<?php echo $leTournoi->event_tarif + 1; ?> €)
                                        							</button>
                                        						</a>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    							else{
                    								?>
                    									<div class="joueur">
                    										<div>
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>";
                    											?>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    						}
                    					}
                    				?>
                    				</div>
                    		</div>
                    		<div class="row">
                    			<h2 class="">Remplaçants</h2> 
                    				<div class="liste_joueur">
                    			    <?php 
                    					foreach ($liste_joueurs_equipe as $key => $value) {
                    						if ($value['em_statut_joueur'] == 6){
                    							if ($value['em_membre_id'] == $_SESSION['id']){
                    								?>
                    									<div class="joueur_user">
                    										<div class="">
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>";
                    											?>
                           									</div>
                           									<div class="">
                           										<a href="pay/creer_utilisateur.php?tournoi=<?php echo $leTournoi->event_id; ?>">
                                        							<button style="margin: 1% 0; padding: 2%;" class="btn btn-success btn-xs">Me desinscrire (a faire) (<?php echo $leTournoi->event_tarif + 1; ?> €)
                                        							</button>
                                        						</a>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    							else{
                    								?>
                    									<div class="joueur>
                    										<div>
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>";
                    											?>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    						}
                    					}
                    				?>
                    				</div>
                    		</div>
                    		<div class="row">
                    			<h2>En cours d'inscription</h2>
                    				<div class="liste_joueur">
                    			    <?php 
                    					foreach ($liste_joueurs_equipe as $key => $value) {
                    						if ($value['em_statut_joueur'] == 2 OR $value['em_statut_joueur'] == 5){
                    							if ($value['em_membre_id'] == $_SESSION['id']){
                    								?>
                    									<div class="joueur_user">
                    										<div class="col-md-6">
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>";
                    											?>
                           									</div>
                           									<div class="col-md-6">
                           										<a href="pay/creer_utilisateur.php?tournoi=<?php echo $leTournoi->event_id; ?>">
                                        							<button style="margin: 1% 0; padding: 2%;" class="btn btn-success btn-xs">Payer de ma place(<?php echo $leTournoi->event_tarif + 1; ?> €)
                                        							</button>
                                        						</a>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    							else{
                    								?>
                    									<div class="joueur">
                    										<div>
                    											<?php
                    												echo $value['membre_pseudo'];
                    												echo '<br/>';
                    												echo "<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>";
                    											?>
                           									</div>
                    									</div>
                    								<?php
                    							}
                    						}
                    					}
                    				?>
                    				</div>
                    		</div>			
                    	</div>

                                    <a href="pay/creer_utilisateur.php?tournoi=<?php echo $leTournoi->event_id; ?>">
                                        <button style="margin: 1% 0; padding: 2%;" class="btn btn-success btn-grand">Payer ma place (<?php echo $leTournoi->event_tarif + 1; ?> €)</button>
                                    </a>

                                    <div class="row" style="margin: 0.5% 0;">
                                        <div class="col-md-6" style="padding-left: 0;">
                                    <!-- Affichage des paramètres pour le capitaine de l'equipe -->
                                    <?php if (recupStatutJoueur($_SESSION["id"], $mon_equipe["team_id"]) == 1){ ?>
                                                <div class="param-team center">
                                                    <h3 class="clic-param espace-bot">Paramètres de l'equipe <span class="glyphicon glyphicon-menu-down right"></span></h3>
                                                    <div>
                                                        <form id="form-param-team" method="post" action="param_team.php?id=<?php echo $mon_equipe['team_id']; ?>&tournoi=<?php echo $leTournoi->event_id; ?>">
                                                            <input style=" margin: auto;" class="form-control" type="text" placeholder="Nom de l'equipe" name="nom-team" value="<?php echo $mon_equipe['team_nom']; ?>"><br />
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
                                                            <input type="submit" name="submit" class="espace-top btn btn-success btn-grand" value="Enregistrer">
                                                        </form>
                                                        <button class="btn btn-danger btn-grand espace-bot suppr-team" mod="suppr" id="<?php echo $mon_equipe['team_id']; ?>">Supprimer l'équipe</button>
                                                    </div>
                                                    Invite tes amis en leur transmettant ce lien : <input style="text-align: center; width: 50%;" type="text" readonly value="<?php echo $param->url_site; ?>invite.php?code_team=<?php echo $mon_equipe["team_code"]; ?>">
                                                </div>
                                            <?php }
                                                // Affichage des joueurs de mon équipe
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

                                        <!-- Affichage du mur de l'équipe -->
                                        <div class="col-md-6" id="mur-equipe-cont">
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
		    		<!-- LES ÉQUIPES -->
		    		<div id="equipes" class="cont">
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
			   							if ($unJoueur["em_membre_paye"] == 1) { $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; } else { $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; }?>
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
			    					<div class="recap_equipe">
			    						<div class="equipe_nom"><h1><?php echo $uneEquipe["team_nom"]; ?></h1></div>
			    						<div class="equipe_nb_joueurs"><h1><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</h1></div>
			    						<?php if (recupStatutJoueur($_SESSION["id"], $uneEquipe["team_id"]) == 1){ ?>
			    							<div class="equipe_btn"><button style="width: 100%;" mod="suppr" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger btn-md">Supprimer</button></div>
			    						<?php }else{
			    							if ($mon_equipe["team_id"] == $uneEquipe["team_id"]){ ?>
			    								<div class="equipe_btn"><button style="width: 100%;" mod="leave" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-danger btn-md">Quitter</button></div>
			    							<?php }elseif (empty($mon_equipe) && compter_membres($uneEquipe["team_id"]) <= $leTournoi->event_joueurs_max){ ?>
			    								<div class="equipe_btn "><button style="width: 100%;" mod="rej" id="<?php echo $uneEquipe['team_id']; ?>" class="btn btn-success btn-md">Rejoindre</button></div>
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
			   							if ($unJoueur["em_membre_paye"] == 1) { $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; } else { $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; }?>
		    								<div class="equipe_joueurs_detail">
		    									<div>
			    									<?php echo $unJoueur["membre_pseudo"]; ?><br />
			    									<?php echo $unJoueur["statut_nom"]; ?>
			    								</div>
			    								<div>
		    										<p class="statut"><?php echo $paye; ?></p>
		    									</div>
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

		    			<?php if(empty($mon_equipe)){ 
		    				?>
                            <hr style="border-color: white;">
		    				<button class="add-team btn btn-success" value="<?php echo $leTournoi->event_id; ?>">Créer mon équipe</button>
		    				<form class="espace-top form-equipe" method="post" action="creer_equipe.php?tournoi=<?php echo $leTournoi->event_id; ?>">
		    					<fieldset>
							    	<div class="form-group">
							        	<div class="col-md-8">
							        		<input type="text" class="form-control" id="inputPseudo" name="nom" placeholder="Nom de l'équipe">
							        	</div>
							        	<div class="col-md-4">
							    			<button type="submit" name="submit" class="btn btn-primary btn-grand" >Ajouter</button>
							    		</div>        	
								    </div>
							    </fieldset>
		    				</form>
		    			<?php } ?>

		    		</div>

		    	</div>
		    </div>
	    <?php
	    }
	    ?>
    </div>

    <?php include 'footer.php'; ?>

	    <script type="text/javascript">

	    	/*$(document).ready(function() { 
	    		$("#equipes").show();
	    	}); */

            $(".show").click(function() {
                $(".show").removeClass("acti");
                $(this).addClass("acti");
                $(".cont").hide();
                var id = $(this).attr("id");
                if (id == "show-mur")
                    $("#mur").show();
                else if (id == "show-mon-equipe")
                    $("#mon_equipe").show();
                else if (id == "show-equipes")
                    $("#equipes").show();
            });

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


