<?php

include '../conf.php';

if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

if (isset($_GET["id"])){
	$id_tournoi = htmlspecialchars(trim($_GET["id"]));
	$req = $db->prepare("SELECT event_orga FROM tournois WHERE event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	$id_orga = $req->fetchColumn();
	if ($_SESSION["id"] != $id_orga)
		header("Location: ../index.php");
}else{
	header("Location: ../index.php");
}

$req = $db->prepare("DELETE FROM messages_mur WHERE mur_tournoi_id = :id_tournoi; DELETE FROM equipes_tournois WHERE et_event_id = :id_tournoi; DELETE FROM tournois WHERE event_id = :id_tournoi; DELETE FROM creneaux WHERE creneau_event_id = :event_id");
$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
$req->bindValue(":event_id", $id_tournoi, PDO::PARAM_INT);
$req->execute();


header("Location: index.php");

?>