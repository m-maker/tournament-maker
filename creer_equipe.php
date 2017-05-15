<?php 

include 'conf.php';

$code = chaineRandom(20);

if (isset($_POST["submit"]) && isset($_GET["tournoi"])){

	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$nom_equipe = htmlspecialchars(trim($_POST["nom"]));

	if (!empty($id_tournoi) && !empty($nom_equipe)){

		/// On récupère le dernier id de la table equipe, et on l'incrémente pour avoir un nouvel id
		$req_id_team = $db->query("SELECT MAX(team_id) FROM equipes;");
		$req_id_team->execute();
		$id_team = $req_id_team->fetchColumn() + 1;

		/// Ajout de l'equipe
		$req_new_team = $db->prepare("INSERT INTO equipes (team_id, team_nom, team_code) VALUES (:id, :nom, :code);");
		$req_new_team->bindValue(":id", $id_team, PDO::PARAM_INT);
		$req_new_team->bindValue(":nom", $nom_equipe, PDO::PARAM_STR);
		$req_new_team->bindValue(":code", $code, PDO::PARAM_STR);
		$req_new_team->execute();

		/// Ajout de l'equipe au tournoi
		$req_et = $db->prepare("INSERT INTO equipes_tournois (et_event_id, et_equipe) VALUES (:id_tournoi, :id_team)");
		$req_et->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
		$req_et->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req_et->execute();

		/// Ajout du membre à l'equipe en tant que capitaine
		$req_em = $db->prepare("INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_membre, :id_team, 1, 0);");
		$req_em->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
		$req_em->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req_em->execute();
		header('Location: feuille_de_tournois.php?tournoi=' . $id_tournoi);

	}else{
		echo "err_champs_vides";
	}

}else{
	echo '403 Accès interdit !!';
}

?>