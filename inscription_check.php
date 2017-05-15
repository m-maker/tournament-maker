<?php 

include "conf.php";

function pseudo_existe($db, $pseudo){
	$req = $db->prepare("SELECT * FROM membres WHERE membre_pseudo = :pseudo");
	$req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
	$req->execute();
	if ($req->rowCount() > 0)
		return true;
	else
		return false;
}

if (isset($_POST["submit"])){

	// Initialisation & sécurisation des données utilisateur
	$pseudo = htmlspecialchars(trim($_POST["pseudo"]));
	$pass = htmlspecialchars(trim($_POST["pass"]));
	$mail = htmlspecialchars(trim($_POST["mail"]));
	$tel = htmlspecialchars(trim($_POST["tel"]));
	$orga = htmlspecialchars(trim($_POST["orga"]));
	var_dump($_POST);

	if (!empty($pseudo) && !empty($pass) && !empty($mail) && !empty($tel)){

		$format_telephone = "#^[0-9]{13}$#";
		if (!(preg_match($format_telephone, $tel))){
			if (filter_var($mail, FILTER_VALIDATE_EMAIL)){	
				if (!pseudo_existe($db, $pseudo)){

					$pass = md5($pass);
					$req = $db->prepare("INSERT INTO membres (membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription) VALUES (:pseudo, :pass, :tel, :mail, :orga, NOW())");
					$req->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
					$req->bindParam(":pass", $pass, PDO::PARAM_STR);
					$req->bindParam(":tel", $tel, PDO::PARAM_STR);
					$req->bindParam(":mail", $mail, PDO::PARAM_STR);
					$req->bindParam(":orga", $orga, PDO::PARAM_INT);
					$req->execute();

					$_SESSION["id"] = $pseudo;
					header("Location: index.php");

				}else{
					// ERREUR PSEUDO EXISTE DEJA
					echo "err_pseudo";
				}
			}else{
				// ERREUR FORMAT MAIL
				echo "err_mail";
			}
		}else{
			// ERREUR FORMAT TELEPHONE
			echo "err_tel";
		}

	}else{
		// ERREUR CHAMPS VIDES
		echo "err_empty";
	}

}

?>