<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2017
 * Time: 13:09
 */

include '../conf.php';

if (!isset($_SESSION["id"]))
    header("Location: connexion.php");

if (isset($_POST["submit"]) && isset($_GET["id"])){

    $id_tournoi = htmlspecialchars(trim($_GET["id"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    if (empty($leTournoi) || $leTournoi == null || $leTournoi->event_orga != $_SESSION["id"])
        header("Location: index.php");

    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($message)){
        $req = $db->prepare("INSERT INTO messages_mur (mur_membre_id, mur_date, mur_contenu, mur_evenement_id) VALUES (:id_membre, NOW(), :msg, :id_tournoi);");
        $req->bindValue(":id_membre", $_SESSION["id"], PDO::PARAM_INT);
        $req->bindValue(":msg", $message, PDO::PARAM_STR);
        $req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
        $req->execute();
        header("Location: mur.php?tournoi=" . $id_tournoi);
    }else{
        echo "Erreur: Votre message ne peut être vide!";
    }

}else{
    echo "403 Accès Interdit !!";
}

?>