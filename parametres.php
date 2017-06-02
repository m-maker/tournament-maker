<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 01/06/2017
 * Time: 23:59
 */

include 'conf.php';

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
    $id_membre = $_SESSION["id"];
    $membre = recupMembreByID($id_membre);
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

        <div id="titre_corps">Mes paramètres</div>

        <form class="form-horizontal" method="post" action="parametres_check.php">
            <h3 class="center bold" style="width: 80%; margin: 2% auto;border-bottom: 1px solid black;">Mes informations</h3>
            <legend class="center">Changer mon pseudo / adresse mail</legend>
            <input class="form-control" id="pseudo" type="text" name="pseudo" placeholder="Votre pseudo" value="<?php echo $membre["membre_pseudo"]; ?>" />
            <input class="form-control" type="email" name="mail" id="mail" placeholder="Votre adresse mail" value="<?php echo $membre['membre_mail']; ?>" />
            <button type="submit" class="form-control btn btn-success">Changer mes informations</button>
        </form>

        <form class="form-horizontal" method="post" action="parametres_check.php">
            <legend class="center">Changer mon mot de passe</legend>
            <input type="password" name="ancien_pass" class="form-control" placeholder="Mot de passe actuel" />
            <input type="password" name="nouveau_pass" class="form-control" placeholder="Nouveau mot de passe" />
            <button type="submit" class="form-control btn btn-success">Changer mes informations</button>
        </form>





    </div>

</div>

</body>
</html>
