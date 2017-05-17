<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["tournoi"]) && isset($_GET["team"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$id_team = htmlspecialchars(trim($_GET["team"]));
	$equipe = recupEquipeByID($id_team);
	$req = $db->prepare("SELECT event_orga FROM tournois WHERE event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	$id_orga = $req->fetchColumn();
	if ($_SESSION["id"] != $id_orga)
		header("Location: index.php");
}else{
	header("Location: index.php");
}

$req = $db->prepare("DELETE FROM equipe_membres WHERE em_team_id = :id_team; DELETE FROM equipes_tournois WHERE et_equipe = :id_team; DELETE FROM equipes WHERE team_id = :id_team;");
$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
$req->execute();
header("Location: gestion_equipes.php?tournoi=" . $id_tournoi);

?>