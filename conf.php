<?php 

session_start();

global $db;
global $param;

function getParams(){
	$db = connexionBdd();
	$req = $db->query("SELECT * FROM parametres;");
	$req->execute();
	return $req->fetch(PDO::FETCH_OBJ);
}

function connexionBdd(){
	$hote = "localhost";
	$db = "tournoi_soccer";
	$user = "root";
	$pass = "";
	try {
		return new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
	} catch (Exception $e) {
	    die('<b>Erreur de connexion à la Bdd :</b> <br>' . $e->getMessage());
	}
}

function recupStatutJoueur($id_joueur, $id_equipe){
	$db = connexionBdd();
	$req = $db->prepare("SELECT em_statut_joueur FROM equipe_membres WHERE em_membre_id = :id_joueur AND em_team_id = :id_team");
	$req->bindValue(":id_joueur", $id_joueur, PDO::PARAM_INT);
	$req->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchColumn();
}

function chaineRandom($car) {
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}

function format_heure_minute($heure){
	$hr = new DateTime($heure);
	return $hr->format("H:i").' h';
}

function recupEquipeJoueur($id_joueur, $id_tournoi){
	$db = connexionBdd();
	$req_equipe = $db->prepare("SELECT * FROM equipes INNER JOIN equipes_tournois ON team_id = et_equipe WHERE et_event_id = :id");
	$req_equipe->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
	$req_equipe->execute();
	while ($equipe = $req_equipe->fetch()){
		$req = $db->prepare("SELECT * FROM equipe_membres WHERE em_team_id = :id_team");
		$req->bindValue(":id_team", $equipe["team_id"], PDO::PARAM_INT);
		$req->execute();
		while ($membres = $req->fetch()) {
			if ($membres["em_membre_id"] == $id_joueur)
				return $equipe;
		}
	}
}

function recupEquipeByID($id_team){
	$db = connexionBdd();
	$req_equipe = $db->prepare("SELECT * FROM equipes WHERE team_id = :id_team");
	$req_equipe->bindValue(":id_team", $id_team, PDO::PARAM_INT);
	$req_equipe->execute();
	return $req_equipe->fetch();
}

function liste_tournois_orga($id_orga){
	$db = connexionBdd();
	$req_liste_tournois = $db->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE event_orga = :orga');
	$req_liste_tournois->bindValue(":orga", $id_orga, PDO::PARAM_INT);
	$req_liste_tournois->execute();
	return $req_liste_tournois->fetchAll();
}

function recupObjetTournoiByID($id){
	$db = connexionBdd();
	$req_tournoi = $db->prepare("SELECT * FROM tournois INNER JOIN lieux ON event_lieu = lieu_id WHERE event_id = :id");
	$req_tournoi->bindValue(":id", $id, PDO::PARAM_INT);
	$req_tournoi->execute();
	return $req_tournoi->fetch(PDO::FETCH_OBJ);
}

function recupererJoueurs($id_equipe){
	$db = connexionBdd();
	$req_joueurs = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membre_id = em_membre_id INNER JOIN statuts_joueurs ON em_statut_joueur = statut_id WHERE em_team_id = :id_team");
	$req_joueurs->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_joueurs->execute();
	return $req_joueurs->fetchAll();
}



function compter_membres($id_equipe) {
	$db = connexionBdd();
	$req_nb_membres = $db->prepare("SELECT COUNT(em_id) FROM equipe_membres WHERE em_team_id = :id_team");
	$req_nb_membres->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_nb_membres->execute();
	return $req_nb_membres->fetchColumn();
}

function recupEquipesTournoi($id_tournoi){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM equipes INNER JOIN equipes_tournois ON team_id = et_equipe WHERE et_event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchAll();
}

$db = connexionBdd();
$param = getParams();

?>