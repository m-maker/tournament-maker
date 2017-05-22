<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["id"]) && isset($_GET["tournoi"]) && isset($_GET["type"])){

	$id_message = htmlspecialchars(trim($_GET["id"]));
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$type = htmlspecialchars(trim($_GET["type"]));

	// On récupere l'id du posteur du message
	if ($type == 0){
		$req_joueur_msg = $db->prepare("SELECT mur_membre_id FROM messages_mur WHERE mur_id = :id_msg");
	}else{
		$req_joueur_msg = $db->prepare("SELECT me_membre_id FROM mur_equipes WHERE me_id = :id_msg");
	}
	$req_joueur_msg->bindValue(":id_msg", $id_message, PDO::PARAM_INT);
	$req_joueur_msg->execute();
	$id_joueur_poste = $req_joueur_msg->fetchColumn();

	// On vérifie que cest bien le joueur en ligne qui a posté le message
	if ($id_joueur_poste == $_SESSION["id"]){
		if ($type == 0){
			$req = $db->prepare("DELETE FROM messages_mur WHERE mur_id = :id_msg");
		}else{
			$req = $db->prepare("DELETE FROM mur_equipes WHERE me_id = :id_msg");
		}
		$req->bindValue(":id_msg", $id_message, PDO::PARAM_INT);
		$req->execute();
		header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);
	}else{
		echo "Ce n'est pas vous qui avez posté ce message. Acces refusé !";
	}

}else{
	echo "403 Acces Interdit !!";
}

?>