<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["id"]) && isset($_GET["tournoi"])){

	$id_message = htmlspecialchars(trim($_GET["id"]));
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));

	// On récupere l'id du posteur du message
	$req_joueur_msg = $db->prepare("SELECT mur_membre_id FROM messages_mur WHERE mur_id = :id_msg");
	$req_joueur_msg->bindValue(":id_msg", $id_message, PDO::PARAM_INT);
	$req_joueur_msg->execute();
	$id_joueur_poste = $req_joueur_msg->fetchColumn();

	// On vérifie que cest bien le joueur en ligne qui a posté le message
	if ($id_joueur_poste == $_SESSION["id"]){
		$req = $db->prepare("DELETE FROM messages_mur WHERE mur_id = :id_msg");
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