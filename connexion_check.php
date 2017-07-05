<?php 

include "conf.php";

if (isset($_POST["pseudo"]) AND isset($_POST["pass"])){
	if (!empty($_POST["pseudo"]) && !empty($_POST["pass"])){

		$pseudo = htmlspecialchars(trim($_POST["pseudo"]));
		$pass = md5(htmlspecialchars(trim($_POST["pass"])));
		var_dump($pass);
		$req = $db->prepare("SELECT * FROM membres INNER JOIN avatars ON membre_avatar_id = avatars.id WHERE membre_pseudo = :pseudo AND membre_pass = :pass");
		$req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
		$req->bindValue(":pass", $pass, PDO::PARAM_STR);
		$req->execute();

		$membre = $req->fetch();
		if (!empty($membre)){
			// Ajout des variables de session
			$_SESSION["id"] = $membre[0];
			$_SESSION["pseudo"] = $membre["membre_pseudo"];
			$_SESSION['membre_orga'] = $membre['membre_orga'];
            $_SESSION["membre_mail"] = $membre["membre_mail"];
            $_SESSION["membre_avatar"] = $membre["avatar_url"];

            $req_upd = $db->prepare("UPDATE membres SET membre_derniere_connexion = NOW(),membre_ip_derniere_connexion = :ip WHERE membres.id = :id");
            $req_upd->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
            $req_upd->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
            $req_upd->execute();

            header('location:home.php');

		}
		else{
			// Pseudo / mdp invalide
            header('location:index.php?erreur=erreur');			
		}

	}
	else{
	    header('location:index.php?erreur=champs_vides');
    }
}

?>