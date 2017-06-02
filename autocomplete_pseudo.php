<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/05/2017
 * Time: 00:10
 */

include 'conf.php';

if (!isset($_SESSION["id"]))
    header("Location: ../connexion.php");

if (isset($_GET["term"])){
    $pseudo = htmlspecialchars(trim($_GET["term"]));
    $req = $db->prepare("SELECT * FROM membres WHERE membre_pseudo LIKE :pseudo AND membre_id != :id");
    $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
    $req->bindValue(":pseudo", "%" . $pseudo . '%', PDO::PARAM_STR);
    $req->execute();
    $tab_pseudo = [];
    while ($liste_membres = $req->fetch())
        $tab_pseudo[] = $liste_membres["membre_pseudo"];
    echo json_encode($tab_pseudo);
}