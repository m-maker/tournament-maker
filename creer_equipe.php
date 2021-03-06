<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
    header("Location: ../index.php");

$code = chaineRandom(20);

if (isset($_POST["submit"]) && isset($_GET["tournoi"])){

	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$nom_equipe = htmlspecialchars(trim($_POST["nom"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    $not_orga_2 = false;
    if ($leTournoi['event_orga2_id'] != null)
        $not_orga_2 = true;

	if (!empty($id_tournoi) && !empty($nom_equipe)){

		/// On récupère le dernier id de la table equipe, et on l'incrémente pour avoir un nouvel id
		$req_id_team = $db->query("SELECT MAX(id) FROM equipes;");
		$req_id_team->execute();
		$id_team = $req_id_team->fetchColumn() + 1;

		/// Ajout de l'equipe
		$req_new_team = $db->prepare("INSERT INTO equipes (id, team_nom, team_code, team_prive, team_event_id) VALUES (:id, :nom, :code, 0, :event_id);");
		$req_new_team->bindValue(":id", $id_team, PDO::PARAM_INT);
		$req_new_team->bindValue(":nom", $nom_equipe, PDO::PARAM_STR);
		$req_new_team->bindValue(":code", $code, PDO::PARAM_STR);
		$req_new_team->bindValue(":event_id", $id_tournoi, PDO::PARAM_INT);
		$req_new_team->execute();
		$req_new_team->closeCursor();

		/// Ajout du membre à l'equipe en tant que capitaine
		$req_em = $db->prepare("INSERT INTO equipe_membres (id, em_team_id, em_statut_joueur_id, em_membre_paye) VALUES (:id_membre, :id_team, 1, 0);");
		$req_em->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
		$req_em->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req_em->execute();
		header('Location: feuille_de_tournois.php?tournoi=' . $id_tournoi);

        $notif_orga = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a crée une équipe nommée <b>' . recupEquipeByID($id_team)['team_nom']. '</b> pour le tournoi "<b>'. $leTournoi['event_titre'] . '</b>" que vous administrez.', $leTournoi['event_orga_id'], date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
        $notif_orga->addNotif();
        if ($not_orga_2){
            $notif_orga_2 = new Notifications('<b>'.$_SESSION["pseudo"].'</b> a crée une équipe nommée <b>' . recupEquipeByID($id_team)['team_nom']. '</b> pour le tournoi <b>'. $leTournoi['event_titre'] . '</b>" que vous administrez.', $leTournoi['event_orga2_id'], date('d-m-Y H:i:s'), 'feuille_de_tournois.php?tournoi=' . $id_tournoi);
            $notif_orga_2->addNotif();
        }

	}else{
		echo "err_champs_vides";
	}

}else{
	echo '403 Accès interdit !!';
}

?>