<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_POST["submit"]) && isset($_GET["id"])){
	
	$message = htmlspecialchars(trim($_POST["message"]));
	$id_tournoi = htmlspecialchars(trim($_GET["id"]));

    if (empty($leTournoi) || $leTournoi == null)
        header("Location: index.php");

	if (!empty($message)){
		$req = $db->prepare("INSERT INTO messages_mur (mur_membre_id, mur_date, mur_contenu, mur_tournoi_id) VALUES (:id_membre, NOW(), :msg, :id_tournoi);");
		$req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
		$req->bindValue(":msg", $message, PDO::PARAM_STR);
		$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
		$req->execute();
		header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);
	}else{
		echo "Erreur: Votre message ne peut être vide!";
	}

}else{
	echo "403 Accès Interdit !!";
}

?>