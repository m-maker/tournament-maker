<?php

include '../conf.php';

if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

$upd = false;
if (isset($_GET["tournoi"]) && isset($_GET["team"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$id_team = htmlspecialchars(trim($_GET["team"]));
	$equipe = recupEquipeByID($id_team);
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    if ($leTournoi->event_orga_2 != $_SESSION["id"] && $leTournoi->event_orga != $_SESSION["id"])
		header("Location: ../index.php");
}else{
	header("Location: ./index.php");
}

if (isset($_GET['id'])){
	$id_joueur = htmlspecialchars(trim($_GET['id']));
	$upd = true;
}

// Si c'est un changement de caracteristiques
if ($upd){

	if (isset($_POST['statut']) && isset($_POST['paye'])){

		$statut = htmlspecialchars(trim($_POST['statut']));
		$paye = htmlspecialchars(trim($_POST['paye']));

		$req = $db->prepare("UPDATE equipe_membres SET em_statut_joueur = :statut, em_membre_paye = :paye WHERE em_membre_id = :id_membre AND em_team_id = :id_team;");
		$req->bindValue(":statut", $statut, PDO::PARAM_INT);
		$req->bindValue(":paye", $paye, PDO::PARAM_INT);
		$req->bindValue(":id_membre", $id_joueur, PDO::PARAM_INT);
		$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
		$req->execute();

		header("Location: gestion_equipes.php?tournoi=" . $id_tournoi);

	}else{
		echo '403 Acces Interdit !!';
	}

// Sinon c'est un ajout de joueur
}else{
	if (isset($_POST["mail"]) && isset($_POST["paye"])){

		$mail = htmlspecialchars(trim($_POST["mail"]));
		$paye = htmlspecialchars(trim($_POST["paye"]));

		if (!empty($mail)){
			$joueur = recupJoueurByMail($mail);

			// Si le joueur existe
			if (!empty($joueur)){

				// On ajoute le joueur à la team
				$req = $db->prepare('INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_membre, :id_team, 2, :paye);');
				$req->bindValue(":id_membre", $joueur["membre_id"], PDO::PARAM_INT);
				$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
				$req->bindValue(":paye", $paye, PDO::PARAM_INT);
				$req->execute();

				$message = 'Vous avez été invité par ' . $_SESSION["pseudo"] . ' à rejoindre l\'équipe ' . $equipe["team_nom"] . ', veuillez confirmer votre adhésion à cette équipe en cliquant sur un des 2 liens ci-dessous :
				<br /><br />
				<a href="' . $param->url_site . 'invite.php?code_team=' . $equipe['team_code'] . '">Cliquez sur ce lien</a><br />
				<a href="' . $param->url_site . 'invite.php?code_team=' . $equipe['team_code'] . '">' . $param->url_site . 'invite.php?code_team=' . $equipe['team_code'] . '</a>';

				$objet = $param->nom_site . ' - Invitation à rejoindre une équipe';
				$nom_exp = $param->nom_site;

				envoyerMail($param->mail_contact, $mail, $objet, $nom_exp, $message);

			// Si le joueur n'existe pas
			}else{

				// Recupere le dernier id pour créer l'id du nouveau membre
				$req_id_membre = $db->query("SELECT MAX(membre_id) FROM membres;");
				$req_id_membre->execute();
				$id_new_membre = $req_id_membre->fetchColumn() + 1;
				
				$pass = chaineRandom(32);

				// Ajout du nouveau membre
				$req_ajout_membre = $db->prepare("INSERT INTO membres (membre_id, membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription) VALUES (:id_membre, :pseudo, :pass, :tel, :mail, 2, NOW());");
				$req_ajout_membre->bindValue(":id_membre", $id_new_membre, PDO::PARAM_INT);
				$req_ajout_membre->bindValue(":pseudo", $mail, PDO::PARAM_STR);
				$req_ajout_membre->bindValue(":pass", $pass, PDO::PARAM_STR);
				$req_ajout_membre->bindValue(":tel", '0000000000', PDO::PARAM_STR);
				$req_ajout_membre->bindValue(":mail", $mail, PDO::PARAM_STR);
				$req_ajout_membre->execute();

				// On ajoute le joueur à la team
				$req = $db->prepare('INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id_membre, :id_team, 2, :paye);');
				$req->bindValue(":id_membre", $id_new_membre, PDO::PARAM_INT);
				$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
				$req->bindValue(":paye", $paye, PDO::PARAM_INT);
				$req->execute();

				$message = 'Vous avez été invité par ' . $_SESSION["pseudo"] . ' à rejoindre l\'équipe ' . $equipe["team_nom"] . ', mais vous n\'avez pas encore de compte sur ' . $param->nom_site . '.<br />
				Veuillez créer un nouveau compte afin de vous identifier et de répondre à l\'invitation !
				<br /><br />
				<a href="' . $param->url_site . 'invite_connexion.php?mail=' . $mail . '&code=' . $pass . '">Cliquez sur ce lien</a><br />
				<a href="' . $param->url_site . 'invite_connexion.php?mail=' . $mail . '&code=' . $pass . '">' . $param->url_site . 'invite_connexion.php?mail=' . $mail . '&code=' . $pass . '</a>';

				$objet = $param->nom_site . ' - Invitation à rejoindre une équipe';
				$nom_exp = $param->nom_site;

				envoyerMail($param->mail_contact, $mail, $objet, $nom_exp, $message);

			}

			header("Location: gestion_equipes.php?tournoi=" . $id_tournoi);

		}else{
			echo 'err_champs_vides';
		}

	}else{
		echo '403 Acces Interdit !!';
	}

}

?>