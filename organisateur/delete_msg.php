<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2017
 * Time: 13:13
 */

include '../conf.php';

if (!isset($_SESSION["id"]))
    header("Location: connexion.php");

if (isset($_GET["id"]) && isset($_GET["tournoi"])){

    $id_message = htmlspecialchars(trim($_GET["id"]));
    $id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);

    if (empty($leTournoi) || $leTournoi == null || $leTournoi->event_orga != $_SESSION["id"] && $leTournoi->event_orga_2 != $_SESSION["id"])
        header("Location: index.php");

    $req_joueur_msg = $db->prepare("SELECT mur_membre_id FROM messages_mur WHERE mur_id = :id_msg");
    $req_joueur_msg->bindValue(":id_msg", $id_message, PDO::PARAM_INT);
    $req_joueur_msg->execute();
    $id_joueur_poste = $req_joueur_msg->fetchColumn();

    // On vérifie que cest bien le joueur en ligne qui a posté le message
    $req = $db->prepare("DELETE FROM messages_mur WHERE mur_id = :id_msg");
    $req->bindValue(":id_msg", $id_message, PDO::PARAM_INT);
    $req->execute();

    header("Location: mur.php?tournoi=" . $id_tournoi);

}else{
    echo "403 Acces Interdit !!";
}

?>