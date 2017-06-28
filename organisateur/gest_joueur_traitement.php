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
    if ($leTournoi["event_orga2_id"] != $_SESSION["id"] && $leTournoi["event_orga"] != $_SESSION["id"])
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

		$req = $db->prepare("UPDATE equipe_membres SET em_statut_joueur_id = :statut, em_membre_paye = :paye WHERE em_membre_id = :id_membre AND em_team_id = :id_team;");
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

        $password_clear = chaineRandom(15);
        $password = md5($password_clear);

		if (!empty($mail)){
			$joueur = recupJoueurByMail($mail);
            //var_dump($joueur);

			// Si le joueur existe
			if (!empty($joueur)){

				// On ajoute le joueur à la team
				$req = $db->prepare('INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur-id, em_membre_paye) VALUES (:id_membre, :id_team, 2, :paye);');
				$req->bindValue(":id_membre", $joueur["membre_id"], PDO::PARAM_INT);
				$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
				$req->bindValue(":paye", $paye, PDO::PARAM_INT);
				$req->execute();

				$message = '<img src=\'".$param->url_site."img/logo_rtt.png\' width=\'50%\'><br />
				Vous avez été invité par ' . $_SESSION["pseudo"] . ' à rejoindre l\'équipe ' . $equipe["team_nom"] . ',
				<br /><br />Il semblerait que vous ayez déja un compte sous cette adresse, Rappel de vos coordonnées :<br /><br />
				Pseudo : <b>'.$joueur["membre_pseudo"].'</b><br/>
				Adresse mail : <b>'.$joueur["membre_mail"].'</b><br><br />
				Si vous avez perdu le mot de passe de ce compte, <a href="recup_pass.php?mail='.$mail.'">Cliquez ici</a><br /><br />
				<h2>Veuillez confirmer votre adhésion à cette équipe en cliquant sur un des 2 liens ci-dessous :</h2>
				<h2><a href="' . $param->url_site . '/invite.php?code_team=' . $equipe['team_code'] . '">Cliquez sur ce lien</a><br /><br />OU<br /><br />
				<a href="' . $param->url_site . '/invite.php?code_team=' . $equipe['team_code'] . '">' . $param->url_site . '/invite.php?code_team=' . $equipe['team_code'] . '</a></h2>';

				$objet = $param->nom_site . ' - Invitation à rejoindre une équipe';
				$nom_exp = $param->nom_site;

				envoyerMail($param->mail_contact, $mail, $objet, $nom_exp, $message);
			// Si le joueur n'existe pas
			}else{

				// Recupere le dernier id pour créer l'id du nouveau membre
				$req_id_membre = $db->query("SELECT MAX(id) FROM membres;");
				$req_id_membre->execute();
				$id_new_membre = $req_id_membre->fetchColumn() + 1;

				$team = recupEquipeByID($id_team);
                $pass = $team["team_code"];
                $pseudo = explode("@", $mail)[0];

                $code = chaineRandom(20);

				// Ajout du nouveau membre
				$req_ajout_membre = $db->prepare("INSERT INTO membres (id, membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription, membre_derniere_connexion, membre_ip_inscription, membre_ip_derniere_connexion, membre_code_validation, membre_validation, membre_dpt_code) VALUES (:id_membre, :pseudo, :pass, 'no', :mail, 0, NOW(), NOW(), :ip, :ip, :code, 0, NULL);");
				$req_ajout_membre->bindValue(":id_membre", $id_new_membre, PDO::PARAM_INT);
				$req_ajout_membre->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
				$req_ajout_membre->bindValue(":pass", $password, PDO::PARAM_STR);
				$req_ajout_membre->bindValue(":mail", $mail, PDO::PARAM_STR);
                $req_ajout_membre->bindValue(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $req_ajout_membre->bindParam(":code", $code, PDO::PARAM_STR);
				$req_ajout_membre->execute();

				// On ajoute le joueur à la team
				$req = $db->prepare('INSERT INTO equipe_membres (em_membre_id, em_team_id, em_statut_joueur_id, em_membre_paye) VALUES (:id_membre, :id_team, 2, :paye);');
				$req->bindValue(":id_membre", $id_new_membre, PDO::PARAM_INT);
				$req->bindValue(":id_team", $id_team, PDO::PARAM_INT);
				$req->bindValue(":paye", $paye, PDO::PARAM_INT);
				$req->execute();

				$message = '<img src=\'".$param->url_site."img/logo_rtt.png\' width=\'50%\'><br />
				Vous avez été invité par ' . $_SESSION["pseudo"] . ' à rejoindre l\'équipe ' . $equipe["team_nom"] . ', mais vous n\'avez pas encore de compte sur ' . $param->nom_site . '.<br />
			    Nous en avons crée un pour vous (vous pouvez modifier les informations de votre compte via les paramètres.
			    <br /><br />Votre pseudo est : <b>'.$pseudo.'</b><br />
				Votre mot de passe est : <b>'.$password_clear.'</b>
				<br /><br />
				<a href="' . $param->url_site . 'invite.php?mail=' . $mail . '&code_team=' . $pass . '">Cliquez sur ce lien</a><br /><br />OU<br /><br />
				<a href="' . $param->url_site . 'invite.php?mail=' . $mail . '&code_team=' . $pass . '">' . $param->url_site . 'invite.php?mail=' . $mail . '&code_team=' . $pass . '</a>';

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