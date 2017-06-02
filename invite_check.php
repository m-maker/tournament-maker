<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 01/06/2017
 * Time: 11:18
 */

include 'conf.php';

if (!isset($_SESSION["id"])){
    header("Location: index.php");
}

if (isset($_POST) && isset($_GET["code"])){
    $code_team = htmlspecialchars(trim($_GET['code']));
    $team = recupEquipeByCode($code_team);

    $tournoi = recupTournoiEquipe($team['team_id']);
    $org_2 = false;
    if (isset($tournoi["event_orga_2"]))
        $org_2 = true;

    if (isset($_POST["yes"])) {

        $req_recup_joueur = $db->prepare("SELECT * FROM equipe_membres WHERE em_membre_id = :id AND em_team_id = :id_team");
        $req_recup_joueur->bindValue(":id", $_SESSION["id"]);
        $req_recup_joueur->bindValue("id_team",$team["team_id"], PDO::PARAM_INT);
        $req_recup_joueur->execute();
        $joueur = $req_recup_joueur->fetch();

        if ($req_recup_joueur->rowCount() > 0) {
            $req = $db->prepare("UPDATE equipe_membres SET em_statut_joueur = 3 WHERE em_membre_id = :id AND em_team_id = :team_id");
            $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
            $req->bindValue(":team_id", $team['team_id'], PDO::PARAM_INT);
            $req->execute();
            var_dump($_SESSION["id"]);
            echo 'lol';
        }else{
            $req = $db->prepare("INSERT INTO equipe_membres(em_membre_id, em_team_id, em_statut_joueur, em_membre_paye) VALUES (:id, :id_team, 3, 0);");
            $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
            $req->bindValue(":team_id", $team['team_id'], PDO::PARAM_INT);
            $req->execute();
        }

        $notif = new Notifications('<b>'.$_SESSION["pseudo"].'</b> à confirmé son invitation à rejoindre l\'équipe <b>'.$team['team_nom'].'</b> du tournoi <b>'.$tournoi["event_titre"].'</b>.', $tournoi['event_orga'], date('d-m-Y H:i:s'), "feuille_de_tournois.php?tournoi=".$tournoi['event_id']);
        $notif->addNotif();

        if ($org_2){
            $notif = new Notifications('<b>'.$_SESSION["pseudo"].'</b> à confirmé son invitation à rejoindre l\'équipe <b>'.$team['team_nom'].'</b> du tournoi <b>'.$tournoi["event_titre"].'</b>.', $tournoi['event_orga_2'], date('d-m-Y H:i:s'), "feuille_de_tournois.php?tournoi=".$tournoi['event_id']);
            $notif->addNotif();
        }

        //header("Location: mes_matchs.php");

    }else{
        $req = $db->prepare("DELETE FROM equipe_membres WHERE em_membre_id = :id_membre AND em_team_id = :team_id");
        $req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
        $req->bindValue(":team_id", $team['team_id'], PDO::PARAM_INT);
        $req->execute();

        $notif = new Notifications('<b>'.$_SESSION["pseudo"].'</b> à décliné son invitation à rejoindre l\'équipe <b>'.$team['team_nom'].'</b> du tournoi <b>'.$tournoi["event_titre"].'</b>.', $tournoi['event_orga'], date('d-m-Y H:i:s'), "feuille_de_tournois.php?tournoi=".$tournoi['event_id']);
        $notif->addNotif();

        if ($org_2){
            $notif = new Notifications('<b>'.$_SESSION["pseudo"].'</b> à décliné son invitation à rejoindre l\'équipe <b>'.$team['team_nom'].'</b> du tournoi <b>'.$tournoi["event_titre"].'</b>.', $tournoi['event_orga_2'], date('d-m-Y H:i:s'), "feuille_de_tournois.php?tournoi=".$tournoi['event_id']);
            $notif->addNotif();
        }

        header('index.php');
    }
}else{
    alert("Acces Interdit");
}