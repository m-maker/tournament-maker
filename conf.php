<?php 

include 'conf/database.php';

session_start();

global $db;
global $param;

function getParams(){
	$db = connexionBdd();
	$req = $db->query("SELECT * FROM parametres;");
	$req->execute();
	return $req->fetch(PDO::FETCH_OBJ);
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
	$req_liste_tournois = $db->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE event_orga = :orga OR event_orga_2 = :orga');
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

function recupJoueurByMail($mail){
	$db = connexionBdd();
	$req_joueurs = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membre_id = em_membre_id INNER JOIN statuts_joueurs ON em_statut_joueur = statut_id WHERE membre_mail = :mail");
	$req_joueurs->bindValue(":mail", $mail, PDO::PARAM_STR);
	$req_joueurs->execute();
	return $req_joueurs->fetch();
}

function envoyerMail($email_expediteur, $email_recepteur, $objet, $nom_exp, $message, $IP = false){
    // Configuration du passage � la ligne
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email_expediteur))
        $passage_ligne = "\r\n";
    else
        $passage_ligne = "\n";

    // Headers du mail
    $headers= 'To: <'.$email_recepteur.'>' .$passage_ligne;
    $headers .= 'From: "'.$nom_exp.'" <'.$email_expediteur.'>' .$passage_ligne;
    $headers .= "Content-type: text/html; charset=utf-8'.$passage_ligne";

    // Mise en forme du message, ajout de l'expediteur
    $message = 'De: '.$nom_exp.' - <br />'.$passage_ligne.$email_expediteur.$passage_ligne.$passage_ligne.'<br /><br />Message: <br />'.$passage_ligne.$passage_ligne.$message.$passage_ligne.$passage_ligne.'';

    // Ajout de l'IP au message si il provient d'un utilisateur
    if ($IP)
        $message .= '<br /><br />IP : '.$_SERVER['REMOTE_ADDR'];

    // Envoi du mail & réponse
    if(mail($email_recepteur, $objet, $message, $headers)){
        return true;
    }else{
        return 'ERR_INCONNU';
    }
}

function recupStatuts(){
	$db = connexionBdd();
	$req = $db->query("SELECT * FROM statuts_joueurs;");
	$req->execute();
	return $req->fetchAll();
}

function recupJoueurByID($id_joueur, $id_team){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membre_id = em_membre_id WHERE membre_id = :id_membre AND em_team_id = :id_team;");
	$req->bindValue(':id_membre', $id_joueur, PDO::PARAM_INT);
	$req->bindValue(':id_team', $id_team, PDO::PARAM_INT);
	$req->execute();
	return $req->fetch();
}

function compte_equipes($id_tournoi) {
	$db = connexionBdd();
	$req = $db->prepare("SELECT COUNT(et_id) FROM equipes_tournois WHERE et_event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchColumn();
}

function compte_joueurs_tournoi($id_tournoi){
	$db = connexionBdd();
	$req = $db->prepare("SELECT COUNT(em_id) FROM equipe_membres INNER JOIN equipes ON em_team_id = team_id INNER JOIN equipes_tournois ON team_id = et_equipe WHERE et_event_id = :id_tournoi;");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchColumn();
}

// Renvoie les invitations à jouer
function recupInvitationsEquipes($membre_id){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM tournois INNER JOIN equipes_tournois ON event_id = et_event_id INNER JOIN equipes ON et_equipe = team_id INNER JOIN equipe_membres on team_id = em_team_id WHERE em_statut_joueur = 2 AND em_membre_id = :id_membre");
	$req->bindValue(":id_membre", $membre_id, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchAll();
}

function recupEquipeByCode($code){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM equipes WHERE team_code = :code;");
	$req->bindValue(":code", $code, PDO::PARAM_STR);
	$req->execute();
	return $req->fetch();
}
function listeDepartements(){
	$db = connexionBdd();
	$req = $db->prepare('SELECT * FROM departements');
	$req->execute();
	return $req->fetchAll();
}
function liste_tournois($dpt){
	$db = connexionBdd();
	$req_dpt = $db->prepare("SELECT * FROM departements WHERE dpt_code = :code");
	$req_dpt->bindValue(":code", $dpt, PDO::PARAM_STR);
	$req_dpt->execute();
	$res_dpt = $req_dpt->fetch();
	$dpt_id = $res_dpt['dpt_id'];
	$req_liste_tournois = $db->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE lieu_dpt_id = :departement_id');
	$req_liste_tournois->execute(array(
		':departement_id' => $dpt_id
		));
	$liste = $req_liste_tournois->fetchAll();
	return $liste;
}
function liste_tournois_membres($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM tournois INNER JOIN equipes_tournois ON event_id = et_event_id INNER JOIN equipes ON et_equipe = team_id INNER JOIN equipe_membres ON team_id = em_team_id WHERE em_membre_id = :id");
    $req->bindValue(':id', $id_membre, PDO::PARAM_INT);
    $req->execute();
    $liste_tournois = [];
    while ($tournois = $req->fetch()){
        $req_liste_tournois = $db->prepare('SELECT * FROM tournois INNER JOIN lieux ON tournois.event_lieu = lieux.lieu_id WHERE event_id = :id');
        $req_liste_tournois->bindValue(":id", $tournois['event_id'], PDO::PARAM_INT);
        $req_liste_tournois->execute();
        $liste_tournois[] = $req_liste_tournois->fetch();
    }
    return $liste_tournois;
}

function liste_tournois_complexe($lieu_id){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM tournois WHERE event_lieu = :event_lieu AND event_date >= NOW() ORDER BY event_date DESC");
    $req->bindValue(':event_lieu', $lieu_id, PDO::PARAM_INT);
    $req->execute();
    $liste_tournois = $req->fetchAll();
    return $liste_tournois;
}

function recupCompteOrga($id_orga){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM compte WHERE compte_membre_id = :id");
    $req->bindValue(":id", $id_orga, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
}

function liste_lieux($dpt_id){    
	$db = connexionBdd();
    $req = $db->prepare("SELECT * FROM lieux WHERE lieu_dpt_id = :dpt_id");
    $req->bindValue(":dpt_id", $dpt_id, PDO::PARAM_STR);
    $req->execute();
    return $req->fetchAll();
}
function recupLieuById ($lieu_id){    
	$db = connexionBdd();
    $req = $db->prepare("SELECT * FROM lieux WHERE lieu_id = :lieu_id");
    $req->bindValue(":lieu_id", $lieu_id, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch();
}

function recupNbJoueurPayeEquipe($id_equipe, $paye = 1){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(em_id) FROM equipe_membres WHERE em_team_id = :id_equipe AND em_membre_paye = :paye;");
    $req->bindValue(":id_equipe", $id_equipe, PDO::PARAM_INT);
    $req->bindValue(":paye", $paye, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function recupImByMangoID($id_mango){
    $db = connexionBdd();
    $req_joueur = $db->prepare('Select * FROM membres INNER JOIN infos_mango ON membre_id = im_membre_id WHERE im_mango_id = :id_user');
    $req_joueur->bindValue(":id_user", $id_mango, PDO::PARAM_INT);
    $req_joueur->execute();
    return $req_joueur->fetch();
}

function recupImByMembreID($id_membre){
    $db = connexionBdd();
    $req_mango = $db->prepare("SELECT * FROM infos_mango WHERE im_membre_id = :id");
    $req_mango->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req_mango->execute();
    return $req_mango->fetch();
}


function recupMessagesMur($id_tournoi){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM messages_mur INNER JOIN membres ON mur_membre_id = membre_id WHERE mur_tournoi_id = :id ORDER BY mur_date DESC");
    $req->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
}

function liste_assoc(){
    $db = connexionBdd();
    $req = $db->query("SELECT * FROM associations;");
    $req->execute();
    return $req->fetchAll();
}
function date_lettres($date){
	$liste_jours[0] = "Dimanche";
	$liste_jours[1] = "Lundi";
	$liste_jours[2] = "Mardi";
	$liste_jours[3] = "Mercredi";
	$liste_jours[4] = "Jeudi";
	$liste_jours[5] = "Vendredi";
	$liste_jours[6] = "Samedi";
	
	$liste_mois[1] = "Janvier";
	$liste_mois[2] = "Février";
	$liste_mois[3] = "Mars";
	$liste_mois[4] = "Avril";
	$liste_mois[5] = "Mai";
	$liste_mois[6] = "Juin";
	$liste_mois[7] = "Juillet";
	$liste_mois[8] = "Août";
	$liste_mois[9] = "Septembre";
	$liste_mois[10] = "Octobre";
	$liste_mois[11] = "Novembre";
	$liste_mois[12] = "Décembre";
}
$db = connexionBdd();
$param = getParams();

?>