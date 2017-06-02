<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_POST["message"]) && isset($_GET["id"])){
	
	$message = htmlspecialchars(trim($_POST["message"]));
	$id_tournoi = htmlspecialchars(trim($_GET["id"]));

	if (!empty($message)){

	    $req_id = $db->query("SELECT MAX(mur_id) FROM messages_mur;");
	    $req_id->execute();
	    $id = $req_id->fetchColumn() + 1;

		$req = $db->prepare("INSERT INTO messages_mur (mur_id, mur_membre_id, mur_date, mur_contenu, mur_tournoi_id) VALUES (:id, :id_membre, NOW(), :msg, :id_tournoi);");
        $req->bindValue(":id", $id, PDO::PARAM_INT);
		$req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
		$req->bindValue(":msg", $message, PDO::PARAM_STR);
		$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
		$req->execute();
		//header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);

		echo '<div class="msg-cont">'.$message.'<span class="delete-msg">
        <a href="delete_msg.php?type=0&id='.$id.'&tournoi='.$id_tournoi.'">X</a></span>                                             
        <div class="sign-msg">
            Par <span>'.$_SESSION['pseudo'].'</span> le <span>'.date("d-m-Y H:i:s").'</span>
        </div></div>';

	}else{
		alert("Erreur: Votre message ne peut être vide!");
	}

}else{
	alert("403 Accès Interdit !!");
}

?>