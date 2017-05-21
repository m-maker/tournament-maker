<?php

	include '../conf.php';

	/*
	 *   ***** FONCTION D'UPLOAD DE FICHIER *****
	 */
	function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)
	{
		//Test1: fichier correctement uploadé
		if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
		//Test2: taille limite
		//if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
		//Test3: extension
		$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
		//if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
		//Déplacement
		return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
	}

	/*
	 *   ***** SECURISATION DES VARIABLES UTILISATEUR *****
	 */
	$dpt = htmlspecialchars(trim($_POST['event_code_postal']));
	$cp = htmlspecialchars(trim($_POST['event_code_postal']));
	$ville = htmlspecialchars(trim($_POST['event_ville']));
	$adresse = htmlspecialchars(trim($_POST['event_adresse']));
	$nom_lieu = htmlspecialchars(trim($_POST['event_lieu_nom']));
	$adresse = htmlspecialchars(trim($_POST['event_adresse']));
	$rib_bic = htmlspecialchars(trim($_POST['event_rib_bic']));
	$rib_iban = htmlspecialchars(trim($_POST['event_rib_iban']));
	$minute_debut = htmlspecialchars(trim($_POST['minute_debut']));
	$heure_debut = htmlspecialchars(trim($_POST['heure_debut']));
	$minute_fin = htmlspecialchars(trim($_POST['minute_fin']));
	$heure_fin = htmlspecialchars(trim($_POST['heure_fin']));
	$titre = htmlspecialchars(trim($_POST['event_titre']));
	$nb_equipes = htmlspecialchars(trim($_POST['event_nb_equipes']));
	$joueurs_max = htmlspecialchars(trim($_POST['event_joueurs_max']));
	$joueurs_min = htmlspecialchars(trim($_POST['event_joueurs_min']));
	$tarif = htmlspecialchars(trim($_POST['tarif']));
	$event_date = htmlspecialchars(trim($_POST['event_date']));
	$restriction = htmlspecialchars(trim($_POST['restriction']));
	$pass = htmlspecialchars(trim($_POST['event_pass']));
	$paiement = htmlspecialchars(trim($_POST['paiement']));
	$tarification_equipe = htmlspecialchars(trim($_POST['event_tarification_equipe']));
	$descriptif = htmlspecialchars(trim($_POST['event_descriptif']));

	/*
	 *   ***** GESTION DU DEPARTEMENT *****
	 */
	// Découpage du code postal
	$dpt_code = substr($dpt, 0, 2);
	// Récuperation de l'id du Département
	$req_dpt_id = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt_id->bindValue('dpt_code', $dpt_code, PDO::PARAM_STR);
	$req_dpt_id->execute();
	$res_dpt_id = $req_dpt_id->fetch();
	$dpt_id = $res_dpt_id['dpt_id'];

	/*
	 *   ***** GESTION DU LIEU *****
	 */
	// On cherche si le lieu existe
	$req_search_lieu = $db->prepare('SELECT * FROM lieux WHERE lieu_nom = :lieu');
	$req_search_lieu->bindValue(":lieu", $nom_lieu, PDO::PARAM_STR);
	$req_search_lieu->execute();
	$lieu = $req_search_lieu->fetch();
	// Si il existe, on prend son id pour le tournoi, sinon on en crée un nouveau
	if ($req_search_lieu->rowCount() > 0){
		$res_lieu_id = $lieu['lieu_id'];
    }else {
        // Récuperation du dernier id de lieu
        $req_lieu_id = $db->query('SELECT MAX(lieu_id) FROM lieux;');
        $req_lieu_id->execute();
        $res_lieu_id = $req_lieu_id->fetchColumn() + 1;
        // Ajout du lieu
        $req_lieu = $db->prepare('INSERT INTO lieux(lieu_id, lieu_cp, lieu_ville, lieu_adresse_l1, lieu_dpt_id, lieu_nom)
			VALUES (:lieu_id, :lieu_cp, :lieu_ville, :lieu_adresse_l1, :lieu_dpt_id, :lieu_nom)');
        $req_lieu->bindValue(":lieu_id", $res_lieu_id, PDO::PARAM_INT);
        $req_lieu->bindValue(":lieu_cp", $cp, PDO::PARAM_STR);
        $req_lieu->bindValue(":lieu_ville", $ville, PDO::PARAM_STR);
        $req_lieu->bindValue(":lieu_adresse_l1", $adresse, PDO::PARAM_STR);
        $req_lieu->bindValue(":lieu_dpt_id", $dpt_id, PDO::PARAM_INT);
        $req_lieu->bindValue(":lieu_nom", $nom_lieu, PDO::PARAM_STR);
        $req_lieu->execute();
    }

	/*
	 *   ***** GESTION DU PAIEMENT EN LIGNE *****
	 */
	if(!empty($_POST['event_rib_bic'])){
		// Récupération du dernier id de rib
        $req_rib_id = $db->query('SELECT MAX(rib_id) FROM rib;');
        $req_rib_id->execute();
        $rib_id = $req_rib_id->fetchColumn() + 1;
        // Ajout du rib
		$req_rib = $db->prepare('INSERT INTO rib(rib_id, rib_bic, rib_iban, rib_membre_id) VALUES (:rib_id, :rib_bic, :rib_iban, :rib_membre_id)');
		$req_rib->bindValue(':rib_id', $rib_id, PDO::PARAM_INT);
        $req_rib->bindValue(':rib_bic', $rib_bic, PDO::PARAM_STR);
        $req_rib->bindValue(':rib_iban', $rib_iban, PDO::PARAM_STR);
        $req_rib->bindValue(':rib_membre_id', $_SESSION["id"], PDO::PARAM_INT);
		$req_rib->execute();
		$paiement = 1;
	}
	else{
		$rib_id = NULL;
		$paiement = 0;
	}

	/*
	 *   ***** REFORMATAGE DES HEURES *****
	 */
	$heure_debut = $heure_debut.':'.$minute_debut.':00';
	$heure_fin = $heure_fin.':'.$minute_fin.':00';

	var_dump($_FILES['icone']['name']);

	/*
	 *   ***** GESTION DE L'ICONE *****
	 */
	$path_img = date("Y-m-d_H-i-s") . '_' . $_FILES["icone"]["name"];
	$upload1 = upload('icone','../img/logo-tournois/' . $path_img,15360, array('png','gif','jpg','jpeg'));

	var_dump($upload1);


	/*
	 *   ***** GESTION DU TOURNOI *****
	 */
	// Mot de pass null si l'evenement est public
	if ($restriction == 0)
		$pass = null;
	// Récupération du dernier ID de tournoi
	$req_event_id = $db->query('SELECT MAX(event_id) FROM tournois');
	$req_event_id->execute();
	$res_event_id = $req_event_id->fetchColumn() + 1;
	// Ajout des informations du tournoi
	$req_organiser_tournoi = $db->prepare('INSERT INTO tournois(event_id, event_titre, event_nb_equipes, event_joueurs_max, event_joueurs_min, event_tarif, event_lieu, event_date, event_heure_debut, event_heure_fin, event_prive, event_pass, event_paiement, event_rib_id, event_tarification_equipe, event_orga, event_img, event_descriptif) 
			VALUES (:event_id, :event_titre, :event_nb_equipes, :event_joueurs_max, :event_joueurs_min, :tarif, :event_lieu, :event_date, :event_heure_debut, :event_heure_fin, :event_prive, :event_pass, :event_paiement, :event_rib_id, :event_tarification_equipe, :event_orga, :event_img, :event_descriptif)');
	$req_organiser_tournoi->bindValue(":event_id", $res_event_id, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_titre", $titre, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_nb_equipes", $nb_equipes, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_joueurs_max", $joueurs_max, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_joueurs_min", $joueurs_min, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":tarif", $tarif, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_lieu", $res_lieu_id, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_date", $event_date, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_heure_debut", $heure_debut, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_heure_fin", $heure_fin, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_prive", $restriction, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_pass", $pass, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_paiement", $paiement, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_rib_id", $rib_id, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_tarification_equipe", $tarification_equipe, PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_orga", $_SESSION["id"], PDO::PARAM_INT);
	$req_organiser_tournoi->bindValue(":event_img", $path_img, PDO::PARAM_STR);
	$req_organiser_tournoi->bindValue(":event_descriptif", $descriptif, PDO::PARAM_STR);
	$req_organiser_tournoi->execute();


/*echo '<br><br />';
var_dump($res_event_id);
var_dump($titre);
var_dump($nb_equipes);
var_dump($joueurs_min);
var_dump($joueurs_max);
var_dump($tarif);
var_dump($res_lieu_id);
var_dump($event_date);
var_dump($heure_debut);
var_dump($heure_fin);
var_dump($restriction);
var_dump($pass);
var_dump($paiement);
var_dump($rib_id);
var_dump($tarification_equipe);
var_dump($_SESSION['id']);
var_dump($descriptif);*/


	//var_dump($res_event_id);
	header('location: ../feuille_de_tournois.php?tournoi='.$res_event_id);

?>
