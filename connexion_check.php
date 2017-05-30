<?php 

include "conf.php";

if (isset($_POST["pseudo"])){
	if (!empty($_POST["pseudo"]) && !empty($_POST["pass"])){

		$pseudo = htmlspecialchars(trim($_POST["pseudo"]));
		$pass = md5(htmlspecialchars(trim($_POST["pass"])));

		$req = $db->prepare("SELECT * FROM membres WHERE membre_pseudo = :pseudo AND membre_pass = :pass OR membre_mail = :pseudo AND membre_pass = :pass");
		$req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
		$req->bindValue(":pass", $pass, PDO::PARAM_STR);
		$req->execute();

		if ($req->rowCount() == 1){

			$membre = $req->fetch();

			// Ajout des variables de session
			$_SESSION["id"] = $membre["membre_id"];
			$_SESSION["pseudo"] = $membre["membre_pseudo"];
			$_SESSION['membre_orga'] = $membre['membre_orga'];
            $_SESSION["membre_mail"] = $membre["membre_mail"];

            alert("Vous êtes à present connecté", 1);
			echo '<script>document.location.replace("index.php");</script>';

		}else{
			// Pseudo / mdp invalide
			alert('Pseudo / mdp incorrects');
		}

	}else{
	    alert('Certains champs sont vides');
    }
}

?>