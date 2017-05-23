<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 22/05/2017
 * Time: 23:31
 */

include 'conf.php';
include 'connect_api.php';

if (!isset($_SESSION["id"]))
    header("connexion.php");

$id_membre = $_SESSION["id"];

$req_mango = $db->prepare("SELECT * FROM infos_mango WHERE im_membre_id = :id");
$req_mango->bindValue(":id", $id_membre, PDO::PARAM_INT);
$req_mango->execute();
$im = $req_mango->fetch();
?>
<html>

    <head>
        <?php include('head.php'); ?>
        <title>Administrer mes tournois</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
        <style>
            .cont-achat {
                background: #f5f5f5;
                padding: 2%;
                margin: 2%;
            }
        </style>

    </head>

<body>

    <!-- HEADER -->
    <?php include('header.php'); ?>
    <!-- CONTENU DE LA PAGE -->

    <div class="title center bold">Les paiements que j'ai effectué</div>

    <div class="container" id="container">

    <?php if ($req_mango->rowCount() > 1){
        $id_wallet =$im['im_wallet_id']; ?>






    <?php }else{ ?>

        <div class="cont-achat">
            Vous n'avez pas encore de compte MangoPay,
        </div>

    <?php } ?>

    </div>

    </body>
    </html>
