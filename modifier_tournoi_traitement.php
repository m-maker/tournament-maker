<?php
	include('conf.php');

	//extraction du département
	$dpt_code = substr($_POST['event_code_postal'], 0, 2);
	echo '$dpt_code -> '.$dpt_code;

	//récupération du code de département
	$req_dpt_id = $db->prepare('SELECT * FROM departements WHERE dpt_code = :dpt_code');
	$req_dpt_id->execute(array(
		'dpt_code' => $dpt_code
		));

	$res_dpt_id = $req_dpt_id->fetch();
	$dpt_id = $res_dpt_id['dpt_code'];

	var_dump($dpt_id);
	var_dump($res_dpt_id);

	//récupération de l'ID du lieu
	$req_lieu = $db->prepare('SELECT * FROM tournois WHERE event_id = :event_id');
	$req_lieu->execute(array(
		'event_id' => $_GET['tournoi']
		));

	$res_lieu_id = $req_lieu->fetch();
	$lieu_id = $res_lieu_id['event_lieu'];

	var_dump($res_lieu_id);
	var_dump($lieu_id);

	//MAJ du lieu
	$req_maj_lieu = $db->prepare('UPDATE lieux
		SET lieu_cp = :lieu_cp, lieu_ville = :lieu_ville, lieu_adresse_l1 = :lieu_adresse_l1, lieu_nom = :lieu_nom, lieu_dpt_id = :lieu_dpt_id
		WHERE lieu_id = :lieu_id');
	$req_maj_lieu->execute(array(
			'lieu_cp' => $_POST['event_code_postal'],
			'lieu_ville' => $_POST['event_ville'],
			'lieu_adresse_l1' => $_POST['event_adresse'],
			'lieu_nom' => $_POST['event_lieu_nom'],
			'lieu_dpt_id' => $dpt_id,
			'lieu_id' => $lieu_id
		));

	//récupération de l'ID rib
	$req_rib = $db->prepare('SELECT * FROM rib INNER JOIN tournois ON rib.rib_id = tournois.event_rib_id WHERE event_id = :id_tournoi');
	$req_rib->execute(array(
		'id_tournoi' => $_GET['tournoi']
		));

	$res_rib_id = $req_rib->fetch();
	$rib_id = $res_rib_id['rib_id'];

	$req_maj_rib = $db->prepare('UPDATE rib
			SET rib_code = :rib_code
			WHERE rib_id = :rib_id');
	$req_maj_rib->execute(array(
		'rib_code' => $_POST['event_rib'],
		'rib_id' => $rib_id
		));
	
	$heure_debut = $_POST['heure_debut'].':'.$_POST['minute_debut'];
	$heure_fin = $_POST['heure_fin'].':'.$_POST['minute_fin'];
	echo '<br/> heure de début -> '.$heure_debut;
	echo '<br/> heure de fin -> '.$heure_fin;
	echo "<br/>".var_dump($_POST);
	echo "<br/>".var_dump($_GET);
	$req_maj_tournoi = $db->prepare('UPDATE tournois 
		SET event_titre = :event_titre, event_nb_equipes = :event_nb_equipes, event_joueurs_max = :event_joueurs_max, event_joueurs_min = :event_joueurs_min, event_tarif = :event_tarif, event_lieu = :event_lieu, event_date = :event_date, event_heure_debut = :event_heure_debut, event_heure_fin = :event_heure_fin, event_prive = :event_prive, event_pass = :event_pass, event_paiement = :event_paiement, event_rib_id = :event_rib_id, event_tarification_equipe = :event_tarification_equipe, event_orga = :event_orga, event_descriptif = :event_descriptif
		WHERE event_id = :event_id');
	$req_maj_tournoi->execute(array(
		':event_titre' => $_POST['event_titre'],
		':event_nb_equipes' => $_POST['event_nb_equipes'],
		':event_joueurs_max' => $_POST['event_joueurs_max'],
		':event_joueurs_min' => $_POST['event_joueurs_min'],
		':event_tarif' => $_POST['tarif'],
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
		':event_descriptif' => $_POST['event_descriptif'],
		':event_id' => $_GET['tournoi']
	));

	//header('location: feuille_de_tournois.php?tournoi='.$_GET['tournoi']);
?>