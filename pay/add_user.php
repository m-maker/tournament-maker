<?php 

//include '../conf.php';
include 'connect_api.php';

if (isset($_POST)){
	
	$nom = htmlspecialchars(trim($_POST["nom"]));
	$prenom = htmlspecialchars(trim($_POST["prenom"]));
	$mail = htmlspecialchars(trim($_POST["mail"]));
	$jour = htmlspecialchars(trim($_POST["jour"]));
	$mois = htmlspecialchars(trim($_POST["mois"]));
	$annee = htmlspecialchars(trim($_POST["annee"]));
	$nation = htmlspecialchars(trim($_POST["nat"]));
	$devise = htmlspecialchars(trim($_POST["devise"]));
	//$adresse = htmlspecialchars(trim($_POST["adresse"]));
	//$ville = htmlspecialchars(trim($_POST["ville"]));
	//$cp = htmlspecialchars(trim($_POST["cp"]));
	$residence= htmlspecialchars(trim($_POST["residence"]));

	$date_naissance = new DateTime($jour."-".$mois."-".$annee);
	$date = new DateTime();

	$ddn = $jour.$mois.$annee;

	$diff_age = $date->diff($date_naissance, true)->format('%R%a days');

	if (!empty($nom) && !empty($prenom) && !empty($mail) && !empty($jour) && !empty($mois) && !empty($annee)){

		if ($diff_age > 6570){

			$User = new MangoPay\UserNatural();
			$User->Email = $mail;
			$User->FirstName = $prenom;
			$User->LastName = $nom;
			$User->Birthday = $date_naissance->getTimestamp();;
			$User->Nationality = $nation;
			$User->CountryOfResidence = $residence;
			$userAdded = $mangoPayApi->Users->Create($User);
			$_SESSION['utilisateur_mango'] = $userAdded;
			$Wallet = new \MangoPay\Wallet();
			$Wallet->Owners = array($userAdded->Id);
			$Wallet->Description = "Porte-monnaie de base";
			$Wallet->Currency = $devise;
			$wallt = $mangoPayApi->Wallets->Create($Wallet);
			/*$User = new MangoPay\UserNatural();
			$User->Email = $mail;
			$User->FirstName = $prenom;
			$User->LastName = $nom;
			$User->Birthday = $jour.$mois.$annee;
			$User->CreationDate = date("d-m-Y H:i:s");
			$User->Nationality = "FR";
			var_dump($User);
			$userAdd = $mangoPayApi->Users->Create($User);
*/
			var_dump($userAdded);
			var_dump($wallt);
		}

	}

}

?>