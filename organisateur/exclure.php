<?php 

include '../conf.php';

if (isset($_GET["tournoi"]) && isset($_GET["team"]) && isset($_GET["id"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$id_team = htmlspecialchars(trim($_GET["team"]));
	$id_joueur = htmlspecialchars(trim($_GET["id"]));
	$equipe = recupEquipeByID($id_team);
	if ($_SESSION["id"] != $leTournoi->event_orga && $_SESSION['id'] != $leTournoi->event_orga_2)
		header("Location: ../index.php");
}else{
	header("Location: ../index.php");
}

$req = $db->prepare("DELETE FROM equipe_membres WHERE em_membre_id = :id_membre AND em_team_id = :id_team;");
$req->bindValue(":id_membre", $id_joueur, PDO::PARAM_INT);
$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
$req->execute();

header('Location: gestion_equipes.php?tournoi=' . $id_tournoi);

?>