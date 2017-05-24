<?php

include '../conf.php';

$upd = false;
if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

if (isset($_GET["tournoi"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    if ($leTournoi->event_orga_2 != $_SESSION["id"] && $leTournoi->event_orga != $_SESSION["id"])
        header("Location: ../index.php");
}else{
    header("Location: ../index.php");
}

if (isset($_GET["id"])){
	$id_team = htmlspecialchars(trim($_GET["id"]));
	$equipe = recupEquipeByID($id_team);
	$upd = true;
}

if (isset($_POST["submit"])){

	var_dump($_POST);
	$nom = htmlspecialchars(trim($_POST["nom"]));
	$etat = htmlspecialchars(trim($_POST["etat-team"]));
	$mdp = htmlspecialchars(trim($_POST["mdp-team"]));
	$code = chaineRandom(20);

	if (!empty($nom) && !empty($etat) && !empty($mdp) || !empty($nom) && empty($etat)){
		
		if (empty($etat))
			$mdp = null;

		// Si cest un update, la requete est un update
		if ($upd){
			$req = $db->prepare("UPDATE equipes SET team_nom = :nom, team_prive = :etat, team_pass = :pass WHERE team_id = :id_team");
			$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		// Sinon, cest un ajout
		}else{
			// Recup nouvel id
			$req_id = $db->query("SELECT MAX(team_id) FROM equipes");
			$req_id->execute();
			$id_new_team = $req_id->fetchColumn() + 1;
			// Création de la requete d'ajout
			$req = $db->prepare("INSERT INTO equipes (team_id, team_nom, team_code, team_prive, team_pass) VALUES (:id, :nom, :code, :etat, :pass);");
			$req->bindValue(":id", $id_new_team, PDO::PARAM_INT);
			$req->bindValue(":code", $code, PDO::PARAM_STR);
		}

		$req->bindValue(":nom", $nom, PDO::PARAM_STR);
		$req->bindValue(":etat", $etat, PDO::PARAM_INT);
		$req->bindValue(":pass", $mdp, PDO::PARAM_STR);
		$req->execute();

		if (!$upd) {
			// Ajout de l'equipe au tournoi
			$req_tournoi = $db->prepare("INSERT INTO equipes_tournois (et_event_id, et_equipe) VALUES (:id_tournoi, :id_team);");
			$req_tournoi->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
			$req_tournoi->bindValue(":id_team", $id_new_team, PDO::PARAM_INT);
			$req_tournoi->execute();
		}

		header("Location: gestion_equipes.php?tournoi=" . $id_tournoi);

	}else{
		echo "err_champs_vides";
	}


}else{
	echo "403 Acces Interdit !!";
}


?>