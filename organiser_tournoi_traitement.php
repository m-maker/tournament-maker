<?php
	include('conf.php');

	$dpt = htmlspecialchars(trim($_POST['event_code_postal']));
	$cp = htmlspecialchars(trim($_POST['event_code_postal']));
	$ville = htmlspecialchars(trim($_POST['event_ville']));
	$adresse = htmlspecialchars(trim($_POST['event_adresse']));
	$nom_lieu = htmlspecialchars(trim($_POST['event_lieu_nom']));
	$adresse = htmlspecialchars(trim($_POST['event_adresse']));
	$rib_code = htmlspecialchars(trim($_POST['event_rib']));
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

	$dpt_code = substr($dpt, 0, 2);
	echo '$dpt_code -> '.$dpt_code;

	$req_dpt_id = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt_id->execute(array(
		'dpt_code' => $dpt_code
		));

	$res_dpt_id = $req_dpt_id->fetch();
	$dpt_id = $res_dpt_id['dpt_id'];

	echo '$dpt_id => '.$dpt_id;
	$req_lieu = $db->prepare('INSERT INTO lieux(lieu_cp, lieu_ville, lieu_adresse_l1, lieu_dpt_id, lieu_nom)
			VALUES (:lieu_cp, :lieu_ville, :lieu_adresse_l1, :lieu_dpt_id, :lieu_nom)');
	$req_lieu->execute(array(
		'lieu_cp' => $cp,
		'lieu_ville' => $ville,
		'lieu_adresse_l1' => $adresse,
		'lieu_dpt_id' => $dpt_id,
		'lieu_nom' => $nom_lieu
		));


	$req_lieu_id = $db->prepare('SELECT MAX(lieu_id) FROM lieux WHERE lieu_adresse_l1 = :lieu_adresse_l1');
	$req_lieu_id->execute(array(
			'lieu_adresse_l1' => $adresse
			));
	$res_lieu_id = $req_lieu_id->fetch();
	var_dump($res_lieu_id);
	$lieu_id = $res_lieu_id[0];

	var_dump($_POST);
	foreach ($_SESSION as $key => $value) {
		echo 'key -> '.$key.'AND value -> '.$value;
	}
	echo '<br/> lieu_id ='.$lieu_id;

	if(!empty($_POST['event_rib'])){
	$req_rib = $db->prepare('INSERT INTO rib(rib_code, rib_membre_id) VALUES (:rib_code, :rib_membre_id)');
	$req_rib->execute(array(
		'rib_code' => $rib_code,
		'rib_membre_id' => $_SESSION['id']
		));
	
	$req_rib_id = $db->prepare('SELECT MAX(rib_id) FROM rib WHERE rib_membre_id = :rib_membre_id');
	$req_rib_id->execute(array(
		'rib_membre_id' => $_SESSION['id']
		));
	$res_rib = $req_rib_id->fetch();
	$rib_id = $res_rib[0];
	}
	else{
		$rib_id = NULL;
	}
	var_dump($rib_id);
	$heure_debut = $heure_debut.':'.$minute_debut;
	$heure_fin = $heure_fin.':'.$minute_fin;
	echo '<br/> heure de début -> '.$heure_debut;
	echo '<br/> heure de fin -> '.$heure_fin;
	$req_organiser_tournoi = $db->prepare('INSERT INTO tournois(event_titre, event_nb_equipes, event_joueurs_max, event_joueurs_min, event_tarif, event_lieu, event_date, event_heure_debut, event_heure_fin, event_prive, event_pass, event_paiement, event_rib_id, event_tarification_equipe, event_orga, event_descriptif) 
			VALUES (:event_titre, :event_nb_equipes, :event_joueurs_max, :event_joueurs_min, :tarif, :event_lieu, :event_date, :event_heure_debut, :event_heure_fin, :event_prive, :event_pass, :event_paiement, :event_rib_id, :event_tarification_equipe, :event_orga, :event_descriptif)'); 
	$req_organiser_tournoi->execute(array(
		':event_titre' => $titre,
		':event_nb_equipes' => $nb_equipes,
		':event_joueurs_max' => $joueurs_max,
		':event_joueurs_min' => $joueurs_min,
		':tarif' => $tarif,
		':event_lieu' => $lieu_id,
		':event_date' => $event_date,
		':event_heure_debut' => $heure_debut,
		':event_heure_fin' => $heure_fin,
		':event_prive' => $restriction,
		':event_pass' => $pass,
		':event_paiement' => $paiement,
		':event_rib_id' => $rib_id,
		':event_tarification_equipe' => $tarification_equipe,
		':event_orga' => $_SESSION['id'],
		':event_descriptif' => $descriptif
		));



	$req_event_id = $db->prepare('SELECT MAX(event_id) FROM tournois WHERE event_orga = :event_orga');
	$req_event_id->execute(array(
		'event_orga' => $_SESSION['id']
		));
	$res_event_id = $req_event_id->fetch();
	$event_id = $res_event_id[0];
	var_dump($res_event_id);
	header('location: feuille_de_tournois.php?tournoi='.$event_id);

?>
