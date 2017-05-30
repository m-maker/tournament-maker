<?php 

include "conf.php";

function pseudo_existe($pseudo){
    $db = connexionBdd();
	$req = $db->prepare("SELECT * FROM membres WHERE membre_pseudo = :pseudo");
	$req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
	$req->execute();
	if ($req->rowCount() > 0)
		return true;
	else
		return false;
}

if (isset($_POST["pseudo"])){

    var_dump($_POST);

	// Initialisation & sécurisation des données utilisateur
	$pseudo = htmlspecialchars(trim($_POST["pseudo"]));
	$pass = htmlspecialchars(trim($_POST["pass"]));
	$mail = htmlspecialchars(trim($_POST["mail"]));
	$tel = htmlspecialchars(trim($_POST["tel"]));
	//$orga = htmlspecialchars(trim($_POST["orga"]));
	//var_dump($_POST);

	if (!empty($pseudo) && !empty($pass) && !empty($mail) && !empty($tel)){

		$format_telephone = "#^[0-9]{13}$#";
		if (!(preg_match($format_telephone, $tel))){
			if (filter_var($mail, FILTER_VALIDATE_EMAIL)){	
				if (!pseudo_existe($pseudo)){

					$pass = md5($pass);
					$req_id = $db->query("SELECT MAX(membre_id) FROM membres;");
					$req_id->execute();
					$id = $req_id->fetchColumn() + 1;

					$req = $db->prepare("INSERT INTO membres (membre_id, membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription) VALUES (:id, :pseudo, :pass, :tel, :mail, 0, NOW())");
                    $req->bindParam(":id", $id, PDO::PARAM_INT);
					$req->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
					$req->bindParam(":pass", $pass, PDO::PARAM_STR);
					$req->bindParam(":tel", $tel, PDO::PARAM_STR);
					$req->bindParam(":mail", $mail, PDO::PARAM_STR);
					$req->execute();

					$_SESSION["id"] = $id;
					$_SESSION["pseudo"] = $pseudo;
					$_SESSION["membre_mail"] = $mail;
					$_SESSION["membre_orga"] = 0;

					alert("Vous êtes maintenant connecté !", 1);
					echo '<script>document.location.replace("index.php");</script>';

				}else{
					// ERREUR PSEUDO EXISTE DEJA
					alert("Ce pseudo est déja pris");
				}
			}else{
				// ERREUR FORMAT MAIL
				alert("Cette adresse mail n'est pas valide");
			}
		}else{
			// ERREUR FORMAT TELEPHONE
			alert("Ce numéro de telephone n'est pas valide");
		}

	}else{
		// ERREUR CHAMPS VIDES
		alert("Certains champs sont vides");
	}

}

?>