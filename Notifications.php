<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 31/05/2017
 * Time: 16:45
 */

class Notifications
{

    private $id;
    private $db;
    private $texte;
    private $membre;
    private $date;
    private $lien;

    public function __construct($texte = null, $membre = null, $date = null, $lien = null) {
        $this->db = connexionBdd();
        $req_id = $this->db->query("SELECT MAX(notif_id) FROM notifications");
        $req_id->execute();
        $this->id = $req_id->fetchColumn() + 1;
        $this->texte = $texte;
        $this->membre = $membre;
        $this->date = $date;
        $this->lien = $lien;
    }

    public static function getAllNotifs($membre){
        $db = connexionBdd();
        $req = $db->prepare("SELECT * FROM notifications WHERE notif_membre_id = :id ORDER BY notif_date DESC");
        $req->bindValue(":id", $membre, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getNewNotifs($membre){
        $db = connexionBdd();
        $req = $db->prepare("SELECT * FROM notifications WHERE notif_membre_id = :id AND notif_vu = 0");
        $req->bindValue(":id", $membre, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getCompteNewNotif($membre){
        $db = connexionBdd();
        $req = $db->prepare("SELECT COUNT(notif_id) FROM notifications WHERE notif_membre_id = :id AND notif_vu = 0");
        $req->bindValue(":id", $membre, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchColumn();
    }

    public function addNotif(){
        $req = $this->db->prepare("INSERT INTO notifications (notif_id, notif_texte, notif_membre_id, notif_date, notif_lien, notif_vu) VALUES (:id, :texte, :membre, NOW(), :lien, 0);");
        $req->bindValue(":id", $this->id, PDO::PARAM_INT);
        $req->bindValue(":texte", $this->texte, PDO::PARAM_STR);
        $req->bindValue(":membre", $this->membre, PDO::PARAM_INT);
        $req->bindValue(":lien", $this->lien, PDO::PARAM_STR);
        $req->execute();
    }

    public static function updateVuNotif($membre) {
        $db = connexionBdd();
        $req = $db->prepare("UPDATE notifications SET notif_vu = 1 WHERE notif_membre_id = :id;");
        $req->bindValue(":id", $membre, PDO::PARAM_INT);
        $req->execute();
    }

}