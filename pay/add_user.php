<?php 

include '../conf.php';
include 'connect_api.php';

if (isset($_POST)){

	$nom = htmlspecialchars(trim($_POST["nom"]));
	$prenom = htmlspecialchars(trim($_POST["prenom"]));
	$mail = htmlspecialchars(trim($_POST["mail"]));
	$jour = htmlspecialchars(trim($_POST["jour"]));
	$mois = htmlspecialchars(trim($_POST["mois"]));
	$annee = htmlspecialchars(trim($_POST["annee"]));
	$nation = htmlspecialchars(trim($_POST["nat"]));
	//$devise = htmlspecialchars(trim($_POST["devise"]));
	/*$adresse = htmlspecialchars(trim($_POST["adresse"]));
	$ville = htmlspecialchars(trim($_POST["ville"]));
	$cp = htmlspecialchars(trim($_POST["cp"]));*/
	$residence= htmlspecialchars(trim($_POST["residence"]));

	$date_naissance = new DateTime($jour."-".$mois."-".$annee);
	$date = new DateTime();

	$ddn = $jour.$mois.$annee;

	$diff_age = $date->diff($date_naissance, true)->format('%R%a days');

	if (!empty($nom) && !empty($prenom) && !empty($mail) && !empty($jour) && !empty($mois) && !empty($annee)){

		if ($diff_age > 6570){

		    // Ajout d'un utilisateur Mango
			$User = new MangoPay\UserNatural();
			$User->Email = $mail;
			$User->FirstName = $prenom;
			$User->LastName = $nom;
			$User->Birthday = $date_naissance->getTimestamp();
			$User->Nationality = $nation;
			$User->CountryOfResidence = $residence;
			$userAdded = $mangoPayApi->Users->Create($User);

			$Wallet = new \MangoPay\Wallet();
			$Wallet->Owners = array($userAdded->Id);
			$Wallet->Description = "Porte-monnaie de base";
			$Wallet->Currency = "EUR";
			$walletAdded = $mangoPayApi->Wallets->Create($Wallet);

            if (isset($_POST["from"])){
                echo json_encode(array("Id" => $userAdded->Id, "WalletId" => $walletAdded->Id));

                $req = $db->prepare("INSERT INTO infos_mango (im_mango_id, im_membre_id, im_wallet_id) VALUES (:id_user, :id_membre, :id_wallet);");
                $req->bindValue(":id_user", $userAdded->Id, PDO::PARAM_INT);
                $req->bindValue(":id_membre", htmlspecialchars(trim($_POST["idMembre"])), PDO::PARAM_INT);
                $req->bindValue(':id_wallet', $walletAdded->Id, PDO::PARAM_INT);
                $req->execute();
            }else {

                $_SESSION['utilisateur_mango'] = serialize($userAdded);
                $_SESSION["wallet_mango"] = serialize($walletAdded);

                $req = $db->prepare("INSERT INTO infos_mango (im_mango_id, im_membre_id, im_wallet_id) VALUES (:id_user, :id_membre, :id_wallet);");
                $req->bindValue(":id_user", $userAdded->Id, PDO::PARAM_INT);
                $req->bindValue(":id_membre", $_SESSION['id'], PDO::PARAM_INT);
                $req->bindValue(':id_wallet', $walletAdded->Id, PDO::PARAM_INT);
                $req->execute();

                header("Location: cartes.php");
            }

		}else{
		    alert("Vous devez être majeur pour payer en ligne");
        }

	}

}

?>