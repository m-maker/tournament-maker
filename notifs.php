<?php include('conf.php');
if (!isset($_SESSION["id"]))
    header("Location: index.php");
?>
<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Yellowtail" rel="stylesheet">
    <link href="css/notifs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Tournois de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php if (!isset($_SESSION["id"])){
    header("Location: index.php");
}
$liste_notifs = Notifications::getAllNotifs($_SESSION["id"]);
include 'header.php'; ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <h1 id="titre_corps">Historique des notifications</h1>

        <div class="notifications">
        <?php
        if (!empty($liste_notifs)) {
            foreach ($liste_notifs as $uneNotif) { ?>
                <a href="<?php echo $uneNotif->notif_lien; ?>">
                    <div class="notif <?php if ($uneNotif->notif_vu == 0) {
                        echo 'non-lu';
                    } else {
                        echo 'lu';
                    } ?>">
                        <?php echo $uneNotif->notif_texte; ?>
                    </div>
                </a>
            <?php }
        }else{ ?>
            <h5 class="center espace-top" style="margin-top: 200px;">
                Vous n'avez pas de notifications pour le moment.<br/>
                Participez à des évenements pour recevoir des nouvelles !
            </h5>
        <?php } ?>
        </div>

    </div>

</div>

<?php Notifications::updateVuNotif($_SESSION["id"]); ?>

<?php include 'footer.php'; ?>

</body>
</html>