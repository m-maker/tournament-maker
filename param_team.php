<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_POST["submit"]) && isset($_GET["id"])){
	
	$id_team = htmlspecialchars(trim($_GET["id"]));
	$nom_team = htmlspecialchars(trim($_POST["nom-team"]));
	$etat_team = htmlspecialchars(trim($_POST["etat-team"]));
	$pass_team = htmlspecialchars(trim($_POST["pass-team"]));

	// On vérifie que cest bien le capitaine de l'equipe
	$req_capi = $db->prepare("SELECT em_membre_id FROM equipes_membres WHERE em_statut_joueur = 1 AND em_team_id = :id_team;");
	$req_capi->bindValue(":id_team", $id_team, PDO::PARAM_INT);
	$req_capi->execute();
	$id_capitaine = $req_capi->fetchColumn();

	if (!empty($id_capitaine) && $_SESSION["id"] == $id_capitaine){
		if (!empty($id_team) && !empty($nom_team)){
			if (!empty($etat_team) && !empty($pass_team) || empty($etat_team)){
				$req = $db->prepare("UPDATE equipes SET team_nom = :nom, team_prive = :etat, team_pass = :pass WHERE team_id = :id");
				$req->bindValue(":nom", $nom_team, PDO::PARAM_STR);
				$req->bindValue(":etat", $etat_team, PDO::PARAM_STR);
				$req->bindValue(":pass", $pass_team, PDO::PARAM_STR);
				$req->bindValue(":id", $id_team, PDO::PARAM_INT);
				$req->execute();
				header("Location: feuille_de_tournois.php?tournoi=" . $_GET["tournoi"]);
			}else{
				echo 'Erreur : Vous devez saisir un mot de passe !';
			}
		}
	}else{
		echo "Vous n'etes pas le capitaine de cette equipe, vous ne pouvez pas la modifier !";
	}

}else{
	echo "403 Acces Interdit !! ";
}

?>