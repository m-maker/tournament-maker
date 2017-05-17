<?php
	include('conf.php');

	$dpt_code = substr($_POST['event_code_postal'], 0, 2);
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
		'lieu_cp' => $_POST['event_code_postal'],
		'lieu_ville' => $_POST['event_ville'],
		'lieu_adresse_l1' => $_POST['event_adresse'],
		'lieu_dpt_id' => $dpt_id,
		'lieu_nom' => $_POST['event_lieu_nom']
		));

	$req_lieu_id = $db->prepare('SELECT MAX(lieu_id) FROM lieux WHERE lieu_adresse_l1 = :lieu_adresse_l1');
	$req_lieu_id->execute(array(
			'lieu_adresse_l1' => $_POST['event_adresse']
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
		'rib_code' => $_POST['event_rib'],
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
	$heure_debut = $_POST['heure_debut'].':'.$_POST['minute_debut'].':00';
	$heure_fin = $_POST['heure_debut'].':'.$_POST['minute_fin'].':00';
	echo '<br/> heure de début -> '.$heure_debut;
	echo '<br/> heure de fin -> '.$heure_fin;
	$req_organiser_tournoi = $db->prepare('INSERT INTO tournois(event_titre, event_nb_equipes, event_joueurs_max, event_joueurs_min, event_tarif, event_lieu, event_date, event_heure_debut, event_heure_fin, event_prive, event_pass, event_paiement, event_rib_id, event_tarification_equipe, event_orga, event_descriptif) 
			VALUES (:event_titre, :event_nb_equipes, :event_joueurs_max, :event_joueurs_min, :tarif, :event_lieu, :event_date, :event_heure_debut, :event_heure_fin, :event_prive, :event_pass, :event_paiement, :event_rib_id, :event_tarification_equipe, :event_orga, :event_descriptif)'); 
	$req_organiser_tournoi->execute(array(
		':event_titre' => $_POST['event_titre'],
		':event_nb_equipes' => $_POST['event_nb_equipes'],
		':event_joueurs_max' => $_POST['event_joueurs_max'],
		':event_joueurs_min' => $_POST['event_joueurs_min'],
		':tarif' => $_POST['tarif'],
		':event_lieu' => $lieu_id,
		':event_date' => $_POST['event_date'],
		':event_heure_debut' => $heure_debut,
		':event_heure_fin' => $heure_fin,
		':event_prive' => $_POST['restriction'],
		':event_pass' => $_POST['event_pass'],
		':event_paiement' => $_POST['paiement'],
		':event_rib_id' => $rib_id,
		':event_tarification_equipe' => $_POST['event_tarification_equipe'],
		':event_orga' => $_SESSION['id'],
		':event_descriptif' => $_POST['event_descriptif']
		));

	$req_organiser_tournoi2 = $db->prepare('INSERT INTO tournois(event_titre, event_nb_equipes, event_joueurs_max, event_joueurs_min, event_tarif, event_lieu, event_date, event_heure_debut, event_heure_fin, event_pass, event_paiement, event_rib_id, event_tarification_equipe, event_orga, event_descriptif) 
			VALUES (:event_titre, :event_nb_equipes, :event_joueurs_max, :event_joueurs_min, :tarif, :event_lieu, :event_date, :event_heure_debut, :event_heure_fin, :event_pass, :event_paiement, :event_rib_id, :event_tarification_equipe, :event_orga, :event_descriptif)'); 
	$req_organiser_tournoi2->execute(array(
		':event_titre' => $_POST['event_titre'],
		':event_nb_equipes' => $_POST['event_nb_equipes'],
		':event_joueurs_max' => $_POST['event_joueurs_max'],
		':event_joueurs_min' => $_POST['event_joueurs_min'],
		':tarif' => $_POST['tarif'],
		':event_lieu' => $lieu_id,
		':event_date' => $_POST['event_date'],
		':event_heure_debut' => $heure_debut,
		':event_heure_fin' => $heure_fin,
		':event_pass' => $_POST['event_pass'],
		':event_paiement' => $_POST['paiement'],
		':event_rib_id' => $rib_id,
		':event_tarification_equipe' => $_POST['event_tarification_equipe'],
		':event_orga' => $_SESSION['id'],
		':event_descriptif' => $_POST['event_descriptif']
		));

	$id_tournoi = $req_organiser_tournoi->fetch();

	//header('location: feuille_de_tournois.php?tournoi='.$id_tournoi['event_id'].'');
?>
