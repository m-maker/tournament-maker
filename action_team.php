<?php 

include ("conf.php");

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["mod"]) && isset($_GET["id"])){

	$id_team = htmlspecialchars(trim($_GET["id"]));
	$action = htmlspecialchars(trim($_GET["mod"]));

	if ($action == "leave"){
		$req = $db->prepare("DELETE FROM equipe_membres WHERE em_membre_id = :id_joueur AND em_team_id = :id_team;");
		$req->bindValue(":id_joueur", $_SESSION["id"], PDO::PARAM_INT);
		$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req->execute();
	}elseif ($action == "rej") {
		$req = $db->prepare("INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_joueur, :id_team, 3, 0)");
		$req->bindValue(":id_joueur", $_SESSION["id"], PDO::PARAM_INT);
		$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req->execute();
	}elseif ($action == "suppr"){
		if (recupStatutJoueur($_SESSION["id"], $id_team) == 1){
			$req = $db->prepare("DELETE FROM equipe_membres WHERE em_team_id = :id_team; DELETE FROM equipes WHERE team_id = :id_team;");
			$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
			$req->execute();
		}
	}

	echo '<script>history.back();</script>';

}

?>