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
	$req = $db->prepare("SELECT em_statut_joueur_id FROM equipe_membres WHERE em_membre_id = :id_joueur AND em_team_id = :id_team");
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
	$req_equipe = $db->prepare("SELECT * FROM equipes WHERE team_event_id = :id");
	$req_equipe->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
	$req_equipe->execute();
	while ($equipe = $req_equipe->fetch()){
		$req = $db->prepare("SELECT * FROM equipe_membres WHERE em_team_id = :id_team");
		$req->bindValue(":id_team", $equipe["id"], PDO::PARAM_INT);
		$req->execute();
		while ($membres = $req->fetch()) {
			if ($membres["em_membre_id"] == $id_joueur)
				return $equipe;
		}
	}
}

function recupEquipeByID($id_team){
	$db = connexionBdd();
	$req_equipe = $db->prepare("SELECT * FROM equipes WHERE id = :id_team");
	$req_equipe->bindValue(":id_team", $id_team, PDO::PARAM_INT);
	$req_equipe->execute();
	return $req_equipe->fetch();
}

function liste_tournois_orga($id_orga){
	$db = connexionBdd();
	$req_liste_tournois = $db->prepare('SELECT * FROM evenements INNER JOIN lieux ON evenements.event_lieu_id = lieux.id WHERE event_orga_id = :orga OR event_orga2_id = :orga ORDER BY event_date');
	$req_liste_tournois->bindValue(":orga", $id_orga, PDO::PARAM_INT);
	$req_liste_tournois->execute();
	return $req_liste_tournois->fetchAll();
}

function recupObjetTournoiByID($id){
	$db = connexionBdd();
	$req_tournoi = $db->prepare("SELECT * FROM evenements AS t1 INNER JOIN lieux AS t2 ON t1.event_lieu_id = t2.id WHERE t1.id = :id");
	$req_tournoi->bindValue(":id", $id, PDO::PARAM_INT);
	$req_tournoi->execute();
	return $req_tournoi->fetch();
}

function recupererJoueurs($id_equipe){
	$db = connexionBdd();
	$req_joueurs = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membres.id = em_membre_id INNER JOIN statut_joueur ON em_statut_joueur_id = statut_joueur.id WHERE em_team_id = :id_team");
	$req_joueurs->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_joueurs->execute();
	return $req_joueurs->fetchAll();
}

function compter_membres($id_equipe) {
	$db = connexionBdd();
	$req_nb_membres = $db->prepare("SELECT COUNT(id) FROM equipe_membres WHERE em_team_id = :id_team");
	$req_nb_membres->bindValue(":id_team", $id_equipe, PDO::PARAM_INT);
	$req_nb_membres->execute();
	return $req_nb_membres->fetchColumn();
}

function recupEquipesTournoi($id_tournoi){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM equipes WHERE team_event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchAll();
}

function recupJoueurByMail($mail){
	$db = connexionBdd();
	$req_joueurs = $db->prepare("SELECT * FROM membres WHERE membre_mail = :mail");
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
    $headers .= 'From: "'.$nom_exp.' <'.$email_expediteur.'>"' .$passage_ligne;
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

function recupTournoiEquipe($id_team){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM evenements INNER JOIN equipes ON evenements.id = team_event_id WHERE equipes.id = :id");
    $req->bindValue(":id", $id_team, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch();
}

function recupStatuts(){
	$db = connexionBdd();
	$req = $db->query("SELECT * FROM statut_joueur;");
	$req->execute();
	return $req->fetchAll();
}

function recupJoueurByID($id_joueur, $id_team){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membres.id = em_membre_id WHERE membres.id = :id_membre AND em_team_id = :id_team;");
	$req->bindValue(':id_membre', $id_joueur, PDO::PARAM_INT);
	$req->bindValue(':id_team', $id_team, PDO::PARAM_INT);
	$req->execute();
	return $req->fetch();
}

function compte_equipes($id_tournoi) {
	$db = connexionBdd();
	$req = $db->prepare("SELECT COUNT(id) FROM equipes WHERE team_event_id = :id_tournoi");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchColumn();
}

function compte_joueurs_tournoi($id_tournoi){
	$db = connexionBdd();
	$req = $db->prepare("SELECT COUNT(equipe_membres.id) FROM equipe_membres INNER JOIN equipes ON em_team_id = equipes.id WHERE equipes.team_event_id = :id_tournoi;");
	$req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
	$req->execute();
	return $req->fetchColumn();
}

// Renvoie les invitations à jouer
function recupInvitationsEquipes($membre_id){
	$db = connexionBdd();
	$req = $db->prepare("SELECT * FROM evenements INNER JOIN equipes ON et_equipe = equipes.id INNER JOIN equipe_membres on equipes.id = em_team_id WHERE em_statut_joueur = 2 AND em_membre_id = :id_membre");
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
	$dpt_id = $res_dpt['id'];
	$req_liste_tournois = $db->prepare('SELECT * FROM evenements as t1 INNER JOIN lieux ON t1.event_lieu_id = lieux.id WHERE lieu_dpt_id = :departement_id AND event_date >= DATE(NOW()) ORDER BY event_date;');
	$req_liste_tournois->execute(array(
		':departement_id' => $dpt_id
		));
	$liste = $req_liste_tournois->fetchAll();
	return $liste;
}
function liste_tournois_membres($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM evenements INNER JOIN equipes ON evenements.id = equipes.team_event_id INNER JOIN equipe_membres ON equipes.id = equipe_membres.em_team_id WHERE equipe_membres.em_membre_id = :id");
    $req->bindValue(':id', $id_membre, PDO::PARAM_INT);
    $req->execute();
    $liste_tournois = [];
    while ($tournois = $req->fetch()){
        $req_liste_tournois = $db->prepare('SELECT * FROM evenements INNER JOIN lieux ON evenements.event_lieu_id = lieux.id WHERE evenements.id = :id');
        $req_liste_tournois->bindValue(":id", $tournois[0], PDO::PARAM_INT);
        $req_liste_tournois->execute();
        $liste_tournois[] = $req_liste_tournois->fetch();
    }
    return $liste_tournois;
}

function liste_tournois_complexe($lieu_id){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM evenements WHERE event_lieu_id = :event_lieu AND event_date >= DATE(NOW()) ORDER BY event_date DESC");
    $req->bindValue(':event_lieu', $lieu_id, PDO::PARAM_INT);
    $req->execute();
    $liste_tournois = $req->fetchAll();
    return $liste_tournois;
}

function recupCompteOrga($id_orga){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM comptes WHERE compte_membre_id = :id");
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

function liste_lieux_dpt_code($dpt_code){    
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM lieux  INNER JOIN departements ON lieux.lieu_dpt_id = departements.id WHERE dpt_code = :dpt_code");
    $req->bindValue(":dpt_code", $dpt_code, PDO::PARAM_STR);
    $req->execute();
    return $req->fetchAll();
}

function recupLieuById ($lieu_id){    
	$db = connexionBdd();
    $req = $db->prepare("SELECT * FROM lieux WHERE id = :lieu_id");
    $req->bindValue(":lieu_id", $lieu_id, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch();
}

function recupNbJoueurPayeEquipe($id_equipe, $paye = 1){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(id) FROM equipe_membres WHERE em_team_id = :id_equipe AND em_membre_paye = :paye;");
    $req->bindValue(":id_equipe", $id_equipe, PDO::PARAM_INT);
    $req->bindValue(":paye", $paye, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function recupImByMangoID($id_mango){
    $db = connexionBdd();
    $req_joueur = $db->prepare('Select * FROM membres INNER JOIN infos_mango ON membres.id = im_membre_id WHERE im_mango_id = :id_user');
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
    $req = $db->prepare("SELECT * FROM messages_mur INNER JOIN membres ON mur_membre_id = membres.id WHERE mur_evenement_id = :id ORDER BY mur_date DESC");
    $req->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
}

function liste_assoc(){
    $db = connexionBdd();
    $req = $db->query("SELECT * FROM membres WHERE membre_orga = 1;");
    $req->execute();
    return $req->fetchAll();
}
function date_lettres($date){
    $date_tab = explode("-", $date);
    $jour = $date_tab[0];
    $mois = $date_tab[2];

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

	foreach ($liste_jours as $k => $unJour){
	    if ($k == $jour){
	        $jour = $unJour;
        }
    }
    foreach ($liste_mois as $k => $unMois){
        if ($k == $mois){
            $mois = $unMois;
        }
    }

    $date = $jour . " " . $date_tab[1] . " " . $mois . " " . $date_tab[3];
	return $date;
}

$db = connexionBdd();
$param = getParams();

function recupMembresMessages($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT DISTINCT pv_expediteur_id, pv_destinataire_id, pv_date FROM messages_prives WHERE (pv_expediteur_id = :id OR pv_destinataire_id = :id) GROUP BY pv_expediteur_id, pv_destinataire_id, pv_date ORDER BY pv_date DESC;");
    $req->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req->execute();
    //$req2 = $db->prepare('SELECT ')
    $liste = $req->fetchAll();
    return  $liste;
}

function liste_messages($id_membre_1, $id_membre_2){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM messages_prives WHERE pv_expediteur_id = :id_m1 AND pv_destinataire_id = :id_m2 OR pv_expediteur_id = :id_m2 AND pv_destinataire_id = :id_m1 ORDER BY pv_date");
    $req->bindValue(":id_m1", $id_membre_1, PDO::PARAM_INT);
    $req->bindValue(":id_m2", $id_membre_2, PDO::PARAM_INT);
    $req->execute();
    $tab_msg = [];
    while ($msg = $req->fetch())
        $tab_msg[] = array("msg" => $msg["pv_message"], "date" => $msg["pv_date"], "exp" => $msg["pv_expediteur_id"]);
    return $tab_msg;
}

function recupComplexes(){
    $db = connexionBdd();
    $req = $db->query("SELECT * FROM lieux INNER JOIN departements ON lieu_dpt_id =id;");
    $req->execute();
    return $req->fetchAll();
}

function membre_existe($colonne, $val){
    $db = connexionBdd();
    if ($colonne == 0)
        $req = $db->prepare("SELECT * FROM membres WHERE membre_mail = :val");
    else
        $req = $db->prepare("SELECT * FROM membres WHERE membre_pseudo = :val");
    $req->bindValue(":val", $val, PDO::PARAM_STR);
    $req->execute();
    //var_dump($req);
    if ($req->rowCount() > 0)
        return true;
    else
        return false;
}

function recupMembreByID($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM membres WHERE id = :id");
    $req->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req->execute();
    return $req->fetch();
}

function compte_msg_envoyes($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(id) FROM messages_prives WHERE pv_expediteur_id = :id");
    $req->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function compte_msg_recus($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(id) FROM messages_prives WHERE pv_destinataire_id = :id");
    $req->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function compte_msg_total($id_membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(id) FROM messages_prives WHERE pv_destinataire_id = :id OR pv_expediteur_id = :id");
    $req->bindValue(":id", $id_membre, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function activer_item($url_page){
    $url_complete = $_SERVER['REQUEST_URI'];
    if (strpos($url_complete, $url_page)){echo 'class="active"';}
}

function compte_event_dpt($dpt_code){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(evenements.id) FROM evenements INNER JOIN lieux ON evenements.event_lieu_id = lieux.id INNER JOIN departements ON lieu_dpt_id = departements.id WHERE dpt_code = :code AND event_date >= DATE(NOW())");
    $req->bindValue(":code", $dpt_code, PDO::PARAM_STR);
    $req->execute();
    return $req->fetchColumn();
}

function recupCapitaine($id_team){
    $db = connexionBdd();
    $req_select_capitaine = $db->prepare("SELECT * FROM membres INNER JOIN equipe_membres ON membres.id = em_membre_id WHERE em_team_id = :id_team AND em_statut_joueur_id = 1");
    $req_select_capitaine->bindValue(":id_team", $id_team, PDO::PARAM_INT);
    $req_select_capitaine->execute();
    $capitaine = $req_select_capitaine->fetch();
    $req_select_capitaine->closeCursor();
    return $capitaine;
}

function alert($msg, $succes = 0){
    $class = 'danger';
    if ($succes == 1){
        $class = 'success';
    }
    $msg = str_replace('�', '&eacute;', $msg);
    echo '<div class="alert alert-dismissible alert-'.$class.'">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>';
    if($succes == 1){ echo 'Bravo! '; }else{ echo 'Erreur! ';}
    echo '</strong> '.$msg.'</div>';
}

function compteMessagesNonVus($membre){
    $db = connexionBdd();
    $req = $db->prepare("SELECT COUNT(id) FROM messages_prives WHERE pv_vu = 0 AND pv_destinataire_id = :membre");
    $req->bindValue(":membre", $membre, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchColumn();
}

function liste_event_terrain($terrain_id) {
    $db = connexionBdd();
    $req = $db->prepare('SELECT * FROM creneaux INNER JOIN evenements ON creneaux.creneau_event_id = evenements.id WHERE creneaux.creneau_terrain_id = :terrain_id');
    $req->execute(array(    
        'terrain_id' => $terrain_id
        ));
    $res = $req->fetchAll();
    return $res;
}

// ---------------------------------------------------------------------

function recupEquipesIncompletes($id_tournoi, $nb_joueur_min){
    $db = connexionBdd();
    $req_equipes = $db->prepare("SELECT * FROM equipes WHERE team_event_id = :id");
    $req_equipes->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
    $req_equipes->execute();
    $equipes_incompletes = array();
    while ($equipes = $req_equipes->fetch()) {
        $compte_membres = compter_membres($equipes[0]);
        if ($compte_membres < $nb_joueur_min){
            $equipes_incompletes[] = $equipes;
        }
    }
    return $equipes_incompletes;
}

function recupEquipesCompletes($id_tournoi, $nb_joueur_min){
    $db = connexionBdd();
    $req_equipes = $db->prepare("SELECT * FROM equipes WHERE team_event_id = :id");
    $req_equipes->bindValue(":id", $id_tournoi, PDO::PARAM_INT);
    $req_equipes->execute();
    $equipes_completes = array();
    while ($equipes = $req_equipes->fetch()) {
        $compte_membres = compter_membres($equipes[0]);
        if ($compte_membres >= $nb_joueur_min)
            $equipes_completes[] = $equipes;
    }
    return $equipes_completes;
}

function recupMessagesEquipe($id_equipe)
{
    $db = connexionBdd();
    $req = $db->prepare("SELECT * FROM mur_equipes INNER JOIN membres ON me_membre_id = membres.id WHERE me_equipe_id = :id_equipe ORDER BY me_date DESC;");
    $req->bindValue(":id_equipe", $id_equipe, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll();
}
    function entete_complexe($jourmin, $jourmax, $lieu_id, $tab_terrains){
        $db = connexionBdd();
        $req_format_terrains = $db->prepare('SELECT * FROM terrain_format');
        $req_format_terrains->execute();
        $format_terrains = $req_format_terrains->fetchAll();
        ?>
            <tr class="entete_complexe_jour">
                <?php   
                    $jour = clone ($jourmin);
                    while ($jour <= $jourmax){
                        ?>
                            <th  colspan="2">
                                <div > <?php echo $jour->format("d/m"); ?> </div>
                            </th>
                        <?php
                        $jour->add( new DateInterval('P1D'));
                    }
                    unset($jour);
                ?>
            </tr>
            <tr>
                <?php   
                    $jour = clone ($jourmin);
                    while ($jour <= $jourmax){
                        foreach ($tab_terrains as $terrain) {
                            ?>
                                <td class="">
                                    <?php echo $terrain['terrain_nom'];
                                    ?>
                                    
                                </td>
                            <?php
                        }
                        $jour->add( new DateInterval('P1D'));
                    }
                    unset($jour);
                ?>
            </tr>
        <?php
    }                           

    function case_complexe ($date_heure, $liste_terrains){
        //  1 = indisponible / 2 = réservé / 9 = morts
        foreach ($liste_terrains as $terrain => $val) {
            $creneau_rempli = 0;

        // On recherche la clé de la ligne dans laquelle le creneau_datetime vaut la date
            foreach ($val['creneaux'] as $creneau => $value) {

                // s'il s'agit du créneau
                if ($value['creneau_datetime'] == $date_heure){


                    if ($value['creneau_statut_id'] == 1) {
                        ?>
                            <td style="margin: 0px; padding: 0px; ">
                                <button  style="margin: 0px; padding: 0px; " class="boutton" data-toggle="popover" data-trigger="focus"  title="Participants" container='body' data-html="true" data-content="coucou
                                    <?php //planning_popover(); ?>">                        
                                    créneau fermé
                                </button>
                            </td>
                        <?php
                    $creneau_rempli = 1;                    
                    }

                    elseif ($value['creneau_statut_id'] == 2) {
                        $date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $date_heure);
                        $date_fin = DateTime::createFromFormat('Y-m-j H:i:s', $value['creneau_datetime_fin']);
                        $demi_heure = DateTime::createFromFormat('i', 30);
                        $nb_demi_heure = 0;
                        while ($date_debut < $date_fin){
                            $nb_demi_heure = $nb_demi_heure + 1;
                            $date_debut->add(new DateInterval('PT30M'));
                        }
                        unset($date_debut);
                        unset($date_fin);
                        $date_debut = DateTime::createFromFormat('Y-m-j H:i:s', $date_heure);
                        $date_fin = DateTime::createFromFormat('Y-m-j H:i:s', $value['creneau_datetime_fin']);
                        $hauteur = 23*$nb_demi_heure;
                        $event;
                        foreach ($val['liste_event'] as $event_key => $event_value) {
                            if ($event_value['creneau_event_id'] == $value['creneau_event_id']){
                                $event = $event_value;
                            }
                        }
                        ?>

                            <td rowspan="<?php echo $nb_demi_heure; ?>" style="height: <?php echo $hauteur; ?>;">
                                <button  class="boutton creneau_match" data-toggle="modal" data-target="#modal_match_<?php echo $value['creneau_event_id']; ?>">
                                créneau réservé <?php echo $nb_demi_heure; ?>
                                </button>

                                     <!-- ***************************************       Modal, pour les tournois /matchs    ***************************************    -->
                                    


                                    <div class="modal fade" id="modal_match_<?php echo $value['creneau_event_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">

                                                <!-- *********    Header de la modal       ***************    -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <p id="modal_match_date">
                                                                <?php echo $date_debut->format('d-m'); ?>
                                                            </p>
                                                            <p id="modal_match_heure">
                                                                <span id="modal_match_heure_debut"> 
                                                                    <?php echo $date_debut->format('H').'H'.$date_debut->format('i');; ?>
                                                                </span>
                                                                <span> - </span>
                                                                <span id="modal_match_heure_fin">
                                                                    <?php echo $date_fin->format('H').'H'.$date_fin->format('i'); ?>
                                                                </span>
                                                            </p>
                                                </div>


                                                <div class="modal-body">

                                                    <!-- **********************         Modal version tournois      ************************    -->
                                                    <div id="modal_match_tournoi" style="">

                                                        <div class="modal_match_tournoi_info">
                                                            <p> 
                                                                <span> Prix : </span>
                                                                <span id="modal_match_prix"> <?php echo $event['event_tarif']; ?></span>
                                                                <span> € </span>
                                                            </p>
                                                            <br/>
                                                            <p>
                                                                <span>descriptif : </span><br/>
                                                                <span id="modal_match_descriptif"> <?php echo $event['event_descriptif']; ?> </span>
                                                            </p>
                                                        </div>

                                                        <hr/>
                                                        
                                                        <div class="modal_match_tournoi_participants">       
                                                            <?php 
                                                                $leTournoi = recupObjetTournoiByID($event['event_id']);
                                                                $equipes_completes = recupEquipesCompletes($event['event_id'], $leTournoi->event_joueurs_min); 
                                                                $nb_equipes_completes = count($equipes_completes);
                                                            ?>
                                                            <p><span class="glyphicon glyphicon-user"></span><span> <?php echo $nb_equipes_completes . ' / ' . $leTournoi->event_nb_equipes; ?></span> équipes complètes</p>
                                                        <?php 
                                                                        $equipes_completes = recupEquipesCompletes($event['event_id'], $leTournoi->event_joueurs_min); 
                                                                        if (!empty($equipes_completes)){
                                                                            foreach ($equipes_completes as $uneEquipe) { 
                                                                                ?>
                                                                                    <div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
                                                                                                <span><?php echo $uneEquipe["team_nom"]; ?></span>
                                                                                                <span><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</span>
                                                                                        <?php 
                                                                                            $joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
                                                                                            $i = 2;
                                                                                         ?>
                                                                                        <div class="equipe-joueurs">
                                                                                            <div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
                                                                                                <?php
                                                                                                    foreach ($joueurs_equipe as $unJoueur) {
                                                                                                        if ($unJoueur["em_membre_paye"] == 1) {
                                                                                                            $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; 
                                                                                                        }
                                                                                                        else { 
                                                                                                            $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; 
                                                                                                        }
                                                                                                        ?>
                                                                                                            <div class="col-md-6 un-joueur">
                                                                                                                <?php echo $unJoueur["membre_pseudo"]; ?><br />
                                                                                                                <?php echo $unJoueur["statut_nom"]; ?>
                                                                                                                <span class="statut"><?php echo $paye; ?></span>
                                                                                                            </div>
                                                                                                        <?php 
                                                                                                    }
                                                                                                ?>
                                                                                           </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        else{
                                                                        } 

                                                                    // Partie pour les équipes incomplètes.    
                                                                    $equipes_incompletes = recupEquipesIncompletes($event['event_id'], $leTournoi->event_joueurs_min);
                                                                    $nb_equipes_incompletes = count($equipes_incompletes);
                                                                        ?>
                                                                        <br/>
                                                                        <p><span class="glyphicon glyphicon-user"></span><span><?php echo $nb_equipes_incompletes?></span> équipes incomplètes</p>
                                                                    <?php
                                                                        
                                                                        if (!empty($equipes_incompletes)){
                                                                            foreach ($equipes_incompletes as $uneEquipe) {
                                                                                ?>
                                                                                    <div class="equipe-cont" id="<?php echo $uneEquipe["team_id"]; ?>">
                                                                                        <span><?php echo $uneEquipe["team_nom"]; ?></span>
                                                                                        <span><?php echo compter_membres($uneEquipe["team_id"]); ?> Joueurs</span>
                                                                                        <?php 
                                                                                            $joueurs_equipe = recupererJoueurs($uneEquipe["team_id"]);
                                                                                            $i = 2;
                                                                                        ?>
                                                                                        <div class="equipe-joueurs">
                                                                                            <div class="row" style="display: none; margin: auto;" id="e-<?php echo $uneEquipe["team_id"]; ?>">
                                                                                                <?php
                                                                                                    foreach ($joueurs_equipe as $unJoueur) {
                                                                                                        if ($unJoueur["em_membre_paye"] == 1) {
                                                                                                            $paye = "<span class='vert'><span class='glyphicon glyphicon-ok'></span> Payé</span>"; 
                                                                                                        }
                                                                                                        else { 
                                                                                                            $paye="<span class='rouge'><span class='glyphicon glyphicon-remove'></span> Non Payé</span>"; 
                                                                                                        }
                                                                                                        ?>
                                                                                                            <div class="equipe_joueurs_detail">
                                                                                                                <div>
                                                                                                                    <?php echo $unJoueur["membre_pseudo"]; ?><br />
                                                                                                                    <?php echo $unJoueur["statut_nom"]; ?>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <p class="statut"><?php echo $paye; ?></p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        <?php 
                                                                                                    } 
                                                                                                ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr/>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        else{
                                                                        } 
                                                                    ?> 
                                                                </div>
                                                                <hr/>
                                                                <div class="row mod-tournoi" id="m-<?php echo $event["event_id"]; ?>">
                                                                    <div class="col-md-3">
                                                                        <a href="mur.php?tournoi=<?php echo $event['event_id']; ?>">
                                                                            <button class="btn btn-default"><span class="glyphicon glyphicon-zoom-in"></span> Publier un message</button>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="modifier_tournoi.php?id=<?php echo $event['event_id']; ?>">
                                                                            <button class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Modifier</button>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="gestion_equipes.php?tournoi=<?php echo $event['event_id']; ?>">
                                                                            <button class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> Gerer les equipes</button>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a href="paiements.php?tournoi=<?php echo $event['event_id']; ?>">
                                                                            <button class="btn btn-default"><span class="glyphicon glyphicon-eur"></span> Encaissements</button>
                                                                        </a>    
                                                                    </div>
                                                                </div>
                                                                <hr/>    
                                                                <div class="cadre_contenu_fdt">
                                                                    <div class="cont_liste-msg-tournoi">
                                                                        <?php $messages = recupMessagesMur($leTournoi->event_id);
                                                                            foreach ($messages as $unMessage) { 
                                                                                ?>
                                                                                    <div class="msg-cont">
                                                                                        <?php 
                                                                                            echo $unMessage["mur_contenu"];
                                                                                            if ($unMessage["membre_id"] == $_SESSION["id"]) {
                                                                                                echo '<span class="delete-msg"><a href="delete_msg.php?type=0&id=' . $unMessage["mur_id"] . '&tournoi=' . $leTournoi->event_id . '">X</a></span>';
                                                                                            }
                                                                                        ?>
                                                                                        <div class="sign-msg">
                                                                                            <span class="sign-msg-date"> Le <?php echo $unMessage["mur_date"]; ?></span>
                                                                                            <br/>
                                                                                            <span class="sign-msg-membre"> Par <?php echo $unMessage["membre_pseudo"]; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr style="margin-right: 30%; margin-left: 30%; margin-bottom: 5px;"/>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                            
                                                                        <a href="suppr_tournoi.php?id=<?php echo $event['event_id']; ?>" style="margin: auto;">
                                                                            <button class="btn btn-default btn-grand" style=" background: slategrey;"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
                                                                        </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </td>
                        <?php
                    $creneau_rempli = 1;
                    }
                }
            }
            if($creneau_rempli == 0){
                $creneau_mort = 0;
                if (isset($val['creneaux_morts'])){ 
                    foreach ($val['creneaux_morts'] as $key_morts => $value_morts) {
                        if ($key_morts == $date_heure){
                        $creneau_mort = 1;
                        }   
                    }
                }
                if ($creneau_mort == 1){
                }
                else{
                    ?>
                        <td class="creneau_indispo " style="margin: 0px; padding: 0px; ">
                            <button type="button" style="margin: 0px; padding: 0px; " class="boutton" data-toggle="modal" data-target="#modal_form_1">                       
                                <?php 
                                    $ex = explode(" ", $date_heure); 
                                    $ex2 = explode(":", $ex[1]);
                                    echo $ex2[0].':'.$ex2[1];
                                ?>
                                <input type="hidden" class="creneau_terrain_id" value="<?php echo $val['id']; ?>">
                                <input type="hidden" class="creneau_heure_debut" value="<?php echo $ex2[0].':'.$ex2[1]; ?>">
                            </button>
                            
                        </td>
                    <?php
                }
            }
        }
    }

function liste_creneaux_libres($lieu_id){
    $db = connexionBdd();
    $req = $db->prepare('SELECT * FROM creneaux INNER JOIN terrains ON creneaux.creneau_terrain_id = terrains.id INNER JOIN lieux ON terrains.terrain_lieu_id = lieux.id WHERE lieux.id = :lieu_id ORDER BY creneaux.creneau_datetime ');
    $req->execute(array(
        'lieu_id' => $lieu_id
        ));
    $res = $req->fetchAll();
    return $res;
}
include 'Notifications.php';
//$Notifs = new Notifications();

?>