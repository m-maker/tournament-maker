<?php 

include ("conf.php");

if (!isset($_SESSION["id"]))
	header("Location: index.php");

if (isset($_GET["mod"]) && isset($_GET["id"])){

	$id_team = htmlspecialchars(trim($_GET["id"]));
	$action = htmlspecialchars(trim($_GET["mod"]));
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$leTournoi = recupObjetTournoiByID($id_tournoi);
    $capitaine = recupCapitaine($id_team);
    $orga = $leTournoi->event_orga;
    $not_orga_2 = false;
    if (isset($leTournoi->event_orga_2)){
        $not_orga_2 = true;
    }

	if ($action == "leave"){
	    // Insertion du membre
		$req = $db->prepare("DELETE FROM equipe_membres WHERE em_membre_id = :id_joueur AND em_team_id = :id_team;");
		$req->bindValue(":id_joueur", $_SESSION["id"], PDO::PARAM_INT);
		$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
		// Récuperation du capitaine de l'equipe

        $notif_capitaine = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a quitté votre équipe <b>' . recupEquipeByID($id_team)['team_nom'] . '</b> pour le tournoi "<b>'. $leTournoi->event_titre . '</b>".', $capitaine['membre_id'], date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
        $notif_capitaine->addNotif();
        $notif_orga = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a quitté l\'équipe <b>' . recupEquipeByID($id_team)['team_nom']. '</b> du tournoi "<b>'. $leTournoi->event_titre . '</b>" que vous administrez.', $orga, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
	    $notif_orga->addNotif();
	    if ($not_orga_2){
            $notif_orga_2 = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a quitté l\'équipe <b>' . recupEquipeByID($id_team)['team_nom']. '</b> du tournoi "<b>'. $leTournoi->event_titre . '</b>" que vous administrez.', $leTournoi->event_orga_2, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
            $notif_orga_2->addNotif();
	    }
    }elseif ($action == "rej") {

	    if (recupEquipeByID($id_team)['team_prive']){
	        if (isset($_POST["pass"])){
	            $pass_team = htmlspecialchars(trim($_POST["pass"]));
	            if ($pass == $pass_team){
                    $req = $db->prepare("INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_joueur, :id_team, 3, 0)");
                    $req->bindValue(":id_joueur", $_SESSION["id"], PDO::PARAM_INT);
                    $req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
                    $req->execute();
                }else{
	                alert("Mot de passe incorrect <a href='feuille_de_tournois.php?id_tournoi=".$id_tournoi."'>Revenir a la page de tournoi</a>");
                }
            }
        }else {
            $req = $db->prepare("INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_joueur, :id_team, 3, 0)");
            $req->bindValue(":id_joueur", $_SESSION["id"], PDO::PARAM_INT);
            $req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
            $req->execute();

            $notif_capitaine = new Notifications('<b>' . $_SESSION["pseudo"] . '</b> a rejoins votre équipe <b>' . recupEquipeByID($id_team)['team_nom'] . '</b> pour le tournoi "<b>' . $leTournoi->event_titre . '</b>".', $capitaine['membre_id'], date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
            $notif_capitaine->addNotif();
            $notif_orga = new Notifications('<b>' . $_SESSION["pseudo"] . '</b> a rejoins l\'équipe <b>' . recupEquipeByID($id_team)['team_nom'] . '</b> du tournoi "<b>' . $leTournoi->event_titre . '</b>" que vous administrez.', $orga, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
            $notif_orga->addNotif();
            if ($not_orga_2) {
                $notif_orga_2 = new Notifications('<b>' . $_SESSION["pseudo"] . '</b> a rejoins l\'équipe <b>' . recupEquipeByID($id_team)['team_nom'] . '</b> du tournoi "<b>' . $leTournoi->event_titre . '</b>" que vous administrez.', $leTournoi->event_orga_2, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
                $notif_orga_2->addNotif();
            }
        }
	}elseif ($action == "suppr"){
		if (recupStatutJoueur($_SESSION["id"], $id_team) == 1){
			$req = $db->prepare("DELETE FROM equipe_membres WHERE em_team_id = :id_team; DELETE FROM equipes WHERE team_id = :id_team;");
			$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);

            $notif_orga = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a supprimé son équipe nommée <b>' . recupEquipeByID($id_team)['team_nom']. '</b> pour le tournoi "<b>'. $leTournoi->event_titre . '</b>" que vous administrez.', $orga, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
            $notif_orga->addNotif();
            if ($not_orga_2){
                $notif_orga_2 = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a supprimé son équipe nommée <b>' . recupEquipeByID($id_team)['team_nom']. '</b> pour le tournoi "<b>'. $leTournoi->event_titre . '</b>" que vous administrez.', $leTournoi->event_orga_2, date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
                $notif_orga_2->addNotif();
            }
            $req->execute();
		}
	}

	header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);

}

?>