<?php 

include "conf.php";


if (isset($_POST["pseudo"])){
	if (!empty($_POST["pseudo"]) && !empty($_POST["pass"])){

		$pseudo = htmlspecialchars(trim($_POST["pseudo"]));
		$pass = md5(htmlspecialchars(trim($_POST["pass"])));
		$req = $db->prepare("SELECT * FROM membres INNER JOIN avatars ON membre_avatar = avatar_id WHERE membre_pseudo = :pseudo AND membre_pass = :pass OR membre_mail = :pseudo AND membre_pass = :pass");
		$req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
		$req->bindValue(":pass", $pass, PDO::PARAM_STR);
		$req->execute();

		$membre = $req->fetch();
		if (!empty($membre)){
			// Ajout des variables de session
			$_SESSION["id"] = $membre["membre_id"];
			$_SESSION["pseudo"] = $membre["membre_pseudo"];
			$_SESSION['membre_orga'] = $membre['membre_orga'];
            $_SESSION["membre_mail"] = $membre["membre_mail"];
            $_SESSION["membre_avatar"] = $membre["avatar_url"];

            $req_upd = $db->prepare("UPDATE membres SET membre_derniere_connexion = NOW(),membre_ip_derniere_connexion = :ip WHERE membre_id = :id");
            $req_upd->bindValue(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
            $req_upd->bindValue(":id", $membre["membre_id"], PDO::PARAM_INT);
            $req_upd->execute();

            alert("Vous êtes à present connecté", 1);

            if(!empty($_POST["return"])) {
                echo '<script>document.location.replace("'.htmlspecialchars(trim($_POST['return'])).'");</script>';
            }else{
                echo '<script>document.location.replace("index.php");</script>';
            }

		}else{
			// Pseudo / mdp invalide
			alert('Pseudo / mdp incorrects');
		}

	}else{
	    alert('Certains champs sont vides');
    }
}

?>