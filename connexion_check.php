<?php 

include "conf.php";

if (isset($_POST["submit"])){
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
			$_SESSION['membre_orga'] = $membre['membre_orga'];
			header("Location: index.php");
		}else{
			// Pseudo / mdp invalide
			echo 'err_pseudo_mdp_invalide';
			//header("Location: index.php");
		}

	}
}

?>