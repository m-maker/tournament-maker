<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["id"]) && isset($_POST["message"])){

	$id_tournoi = htmlspecialchars(trim($_GET["id"]));
	$message = htmlspecialchars(trim($_POST["message"]));

	if (!empty($message)){
		$equipe = recupEquipeJoueur($_SESSION["id"], $id_tournoi);
		if (!empty($equipe)){
			$req = $db->prepare("INSERT INTO mur_equipes (me_date, me_contenu, me_membre_id, me_equipe_id) VALUES (NOW(), :msg, :id_membre, :id_team);");
			$req->bindValue(":msg", $message, PDO::PARAM_STR);
			$req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
			$req->bindValue(":id_team", $equipe["team_id"], PDO::PARAM_INT);
			$req->execute();
			header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);
		}else{
			echo "Erreur: vous n'appartenez à aucune équipe de ce tournoi!";
		}
	}else{
		echo "Erreur: Votre message ne peut être vide!";
	}

}else{
	echo "403 Accès Interdit !!";
}

?>