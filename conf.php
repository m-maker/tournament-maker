<?php 

session_start();

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

global $db;
$db = connexionBdd();

?>