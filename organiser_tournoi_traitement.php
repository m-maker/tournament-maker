<?php
	include('conf.php');

	$dpt = substr($_POST['event_code_postal'], 2, 2);
	
	$req_lieu = $db->prepare('INSERT INTO lieux(lieu_cp, lieu_ville, lieu_adresse_l1, lieu_dpt_id)
			VALUES (:cp, :ville, :adresse, :dpt)');
	$req_lieu->execute(array(
		'cp' => $_POST['event_code_postal'],
		'ville' => $_POST['event_ville'],
		'adresse' => $_POST['event_adresse'],
		'dpt' => $dpt
		));

	$req_lieu_id = $db->prepare('SELECT MAX(lieu_id) FROM lieux WHERE lieu_ville = :ville AND adresse = :adresse');
	$req_lieu_id->execute(array(
			'ville' => $_POST['event_ville'],
			'adresse' => $_POST['event_adresse']
			));
	$res_lieu_id = $req_lieu_id->fetch();
	$lieu_id = $res_lieu_id['lieu_id'];

	$req_organiser_tournoi = $db->prepare('INSERT INTO tournoi (event_titre, event_lieu, event_date, event_nb_heure_jeu, event_heure_debut, event_heure_fin, event_nb_joueur_min, event_nb_joueur_max, event_tarification, event_tarif, event_paiement, event_rib, event_prive, event_pass, event_orga) 
			VALUES (:event_titre, :lieu, :event_date, :event_nb_heure_jeu, :event_heure_debut, :event_heure_fin, :event_nb_joueur_min, :event_nb_joueur_max, :event_tarification, :event_tarif, :event_paiement, :event_rib, :event_prive, :event_pass, :event_orga');

	$req_organiser_tournoi->execute(array(
		'event_titre' => $_POST['event_titre'],
		'lieu' => $lieu_id,
		'event_date' => $_POST['event_date'],
		'event_nb_heure_jeu' => $_POST['event_nb_heure_jeu'],
		'event_heure_debut' => $_POST['event_heure_debut'],
		'event_heure_fin' => $_POST['event_heure_fin'],
		'event_nb_joueur_min' => $_POST['event_nb_joueur_min'],
		'event_nb_joueur_max' => $_POST['event_nb_joueur_max'],
		'event_tarification' => $_POST['tarification'],
		'even_tarif =' => $_POST['tarif'],
		'event_paiement' => $_POST['paiement'],
		'event_rib' => $_POST['event_rib'],
		'event_prive' => $_POST['restriction'],
		'event_pass' => $_POST['event_pass'],
		'event_orga' => $_SESSION['id']
		));

	$req_id_tournois = $db->prepare('SELECT MAX(event_id) FROM tournois WHERE event_orga = :event_orga');
		$req_id_tournois->execute(array(
			'event_orga' => $_SESSION['id']
			));
	$id_tournoi = $req_organiser_tournoi->fetch();

	//header('location: feuille_de_tournois.php?tournoi='.$id_tournoi['event_id'].'');
?>
