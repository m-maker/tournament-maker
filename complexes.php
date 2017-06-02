<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 02/06/2017
 * Time: 11:57
 */

include 'conf.php';

$complexe = null;
if (isset($_GET['id'])){
    $id_lieu = htmlspecialchars(trim($_GET["id"]));
    $complexe = recupLieuById($id_lieu);
}
?>
<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Yellowtail" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Tournois de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php if (isset($_SESSION["id"])){
    include('header.php');
} ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])){
        include('volet.php');
    }?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">

        <?php if (isset($_GET['id'])){ ?>
            <div id="titre_corps"><?php echo $complexe["lieu_id"]; ?></div>

        <?php }elseif (isset($_GET["dpt"])){ ?>
            <div id="titre_corps">Les complexes</div>

        <?php }else{ ?>
            <div
        <?php } ?>




    </div>

</div>

</body>
</html>
