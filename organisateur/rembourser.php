<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2017
 * Time: 11:23
 */

include '../conf.php';
include 'connect_api.php';

/// VERIFICATIONS DE SECURITE
if (!isset($_GET['tournoi']) || !isset($_GET["transfert"]))
    header("Location: index.php");
$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
$id_transfert = htmlspecialchars(trim($_GET["transfert"]));
$id_membre = htmlspecialchars(trim($_GET["membre"]));
$leTournoi = recupObjetTournoiByID($id_tournoi);
if (empty($leTournoi) || $leTournoi == null || $leTournoi->event_orga != $_SESSION["id"])
    header('Location: index.php');

/// Récupérations infos mangos
$im = recupImByMembreID($id_membre);

/// ACTIONS
try {
    $Refund = new \MangoPay\Refund();
    $Refund->Tag = "Remboursement d'une place : " . $leTournoi->event_titre;
    $Refund->AuthorId = $im["im_mango_id"];
    $Result = $mangoPayApi->Transfers->CreateRefund($id_transfert, $Refund);
    var_dump($Result);
} catch(MangoPay\Libraries\ResponseException $e) {
    echo 'Erreur de réponse HTTP. Une ou plusieures informations requises sont manquantes ou incorrectes : ' . $e->getMessage();
} catch(MangoPay\Libraries\Exception $e) {
    echo 'Une erreur inconnue s\'est produite : ' . $e->getMessage();
}
