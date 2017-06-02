<?php 

include 'conf.php';

if (!isset($_SESSION["id"]))
	header("Location: connexion.php");

if (isset($_GET["id"]) && isset($_POST["message"])){

	$id_tournoi = htmlspecialchars(trim($_GET["id"]));
	$message = htmlspecialchars(trim($_POST["message"]));

	if (!empty($message)){
		$equipe = recupEquipeJoueur($_SESSION["id"], $id_tournoi);
		if (!empty($equipe)){

		    $req_id = $db->query("SELECT MAX(me_id) FROM mur_equipes;");
		    $req_id->execute();
		    $id = $req_id->fetchColumn() + 1;

			$req = $db->prepare("INSERT INTO mur_equipes (me_id, me_date, me_contenu, me_membre_id, me_equipe_id) VALUES (:id, NOW(), :msg, :id_membre, :id_team);");
            $req->bindValue(":id", $id, PDO::PARAM_INT);
			$req->bindValue(":msg", $message, PDO::PARAM_STR);
			$req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
			$req->bindValue(":id_team", $equipe["team_id"], PDO::PARAM_INT);
			$req->execute();
			//header("Location: feuille_de_tournois.php?tournoi=" . $id_tournoi);

            echo '<div class="msg-cont">'.$message.'<span class="delete-msg">
        <a href="delete_msg.php?type=1&id='.$id.'&tournoi='.$id_tournoi.'">X</a></span>                                             
        <div class="sign-msg">
            Par <span>'.$_SESSION['pseudo'].'</span> le <span>'.date("d-m-Y H:i:s").'</span>
        </div></div>';

		}else{
			alert("Erreur: vous n'appartenez à aucune équipe de ce tournoi!");
		}
	}else{
		alert("Erreur: Votre message ne peut être vide!");
	}

}else{
	alert("403 Accès Interdit !!");
}

?>