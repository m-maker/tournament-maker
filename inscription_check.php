<?php 

include "conf.php";

if (isset($_POST["pseudo"])){

    //var_dump($_POST);

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
				if (!membre_existe(1, $pseudo)){
				    if (!membre_existe(0, $mail)) {

                        $pass = md5($pass);
                        $req_id = $db->query("SELECT MAX(id) FROM membres;");
                        $req_id->execute();
                        $id = $req_id->fetchColumn() + 1;
                        $params =getParams();

                        $code = chaineRandom(20);

                        $req = $db->prepare("INSERT INTO membres (id, membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription, membre_derniere_connexion, membre_ip_inscription, membre_ip_derniere_connexion, membre_code_validation, membre_validation, membre_dpt_code, membre_avatar_id) VALUES (:id, :pseudo, :pass, :tel, :mail, 0, NOW(), NOW(), :ip, :ip, :code, 0, 33, 1)");
                        $req->bindParam(":id", $id, PDO::PARAM_INT);
                        $req->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
                        $req->bindParam(":pass", $pass, PDO::PARAM_STR);
                        $req->bindParam(":tel", $tel, PDO::PARAM_STR);
                        $req->bindParam(":mail", $mail, PDO::PARAM_STR);
                        $req->bindParam(":code", $code, PDO::PARAM_STR);
                        $req->bindParam(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                        $req->execute();

                        $_SESSION["id"] = $id;
                        $_SESSION["pseudo"] = $pseudo;
                        $_SESSION["membre_mail"] = $mail;
                        $_SESSION["membre_orga"] = 0;
                        $_SESSION["membre_avatar"] = "img/avatars/nice.png";
                        //$_SESSION["membre_valide"] = 0;

                        $message = "<html>
                                        <head>
                                            <style>
                                                body { text-align: center; }
                                                a { color: darkgreen; font-size: 25px; }
                                                a:hover { color: darkslategrey; }
                                            </style>
                                        </head>
                                        <body style='text-align: center;'>
                                            <img src='" . $param->url_site . "img/logo_rtt.png' width='50%'><br />
                                            Vous avez bien crée votre compte sur " . $param->nom_site . ", 
                                            Merci de cliquer sur le lien suivant afin de vérifier votre adresse mail et de profiter de l'intégralité des fonctionnalités<br />
                                            Bienvenu sur notre site, nous vous souhaitons une agréable navigation et esperons que nous vous seront utiles<br /><br />
                                            
                                            <a href='" . $param->url_site . "confirm.php?code=" . $code . ";'>Cliquez ici</a>
                                            <br /> OU<br />
                                            <a href='" . $param->url_site . "confirm.php?code=" . $code . "'>" . $param->url_site . "confirm.php?code=" . $code . "</a>
                                        </body>
                                    </html>";

                        $objet = $param->nom_site . ' - Confirmation de votre compte';
                        $nom_exp = $param->nom_site;

                        envoyerMail($param->mail_contact, $mail, $objet, $nom_exp, $message);

                        alert("Vous êtes maintenant connecté !", 1);

                        header("location:index.php");
                    }
                    else{
                        header("location:index.php?erreur=inscription_mail_pseudo");
                    }
                }else{
                    // ERREUR PSEUDO EXISTE DEJA
                    header("location:index.php?erreur=inscription_pseudo&pseudo=".$_POST['pseudo']);
                }
            }else{
                // ERREUR FORMAT MAIL
                header("location:index.php?erreur=inscription_mail");
			}
		}else{
			// ERREUR FORMAT TELEPHONE
            header("location:index.php?erreur=inscription_tel");
		}

	}else{
		// ERREUR CHAMPS VIDES
        header("location:index.php?erreur=champs_vides");
	}

}

?>