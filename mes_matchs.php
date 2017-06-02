<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 20/05/2017
 * Time: 12:30
 */



include 'conf.php';


if (!isset($_SESSION['id'])){
    header("Location: connexion.php");
}
else{

    $liste_tournois = liste_tournois_membres($_SESSION["id"]);
    //var_dump($liste_tournois);
    ?>

    <html>

    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" href="css/liste_tournois.css">
        <link rel="stylesheet" type="text/css" href="css/mes_matchs.css">
        <title>Les tournois / matchs auquels je suis inscrit</title>
        <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

    </head>

    <body>

    <!-- HEADER -->
    <?php include('header.php'); ?>

    <!-- CONTENU DE LA PAGE -->
    <div id="page">

        <!-- VOLET -->
        <?php include('volet.php'); ?>

        <!-- CONTENU DE LA PAGE -->
        <div id="corps">
            <h1 id="titre_corps">Mes Tournois / Matchs</h1>

            <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->


            <!-- AFFICHAGE DES TOURNOIS -->


                <div style="color: black;">
                    <?php
                    if (!empty($liste_tournois) && $liste_tournois[0] != null){
                        foreach ($liste_tournois AS $event){
                            $heure_debut = format_heure_minute($event['event_heure_debut']);
                            $heure_fin = format_heure_minute($event['event_heure_fin']);
                            $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
                            if ($event['event_prive'] == 1){
                                $color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";
                            }
                            $pay = "<span class='rouge'>Refusé</span>";
                            if ($event['event_paiement'] == 1){
                                $pay="<span class='vert'>Accepté</span>";
                            }
                            $desc = $event['event_descriptif'];
                            if ($event['event_descriptif'] == NULL || empty($event['event_descriptif'])){
                                $desc = 'Pas de description.';
                            }
                            $team = "par équipe";
                            if ($event['event_tarification_equipe'] == 0){
                                $team="par joueur";
                            }
                            ?>  <div class="recap-event">
                                    <div class='titre-liste-tournoi'>
                                        <?php echo $event['event_titre'] ?>
                                        <br>
                                        <p style='font-size: 15px;'>
                                            <span class="glyphicon glyphicon-calendar"></span> Le <span class="bold"><?php echo $event['event_date'] ?> </span> de
                                            <span class="bold"><?php echo $heure_debut?></span> à <span class="bold"><?php $heure_fin?></span>
                                        </p>
                                    </div>
                                    <div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
                        <div class="row">
                            <div class="col-lg-4" style="text-align: left;">
                                <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $event["lieu_nom"];?></span></p>
                                <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                                <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($event['event_id']) . ' / ' . $event['event_nb_equipes']; ?></span> équipes inscrites</p>
                            </div>
                            <div class="col-lg-5 espace-top" style="text-align: left;">
                                <span class="glyphicon glyphicon-info-sign"></span>
                                <?php
                                if (strlen($desc) > 120) {
                                    echo substr($desc, 0, 120)  . '...';
                                }else{
                                    echo $desc;
                                } ?>
                            </div>
                            <div class="col-lg-3 prix-team">
                                <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $event['event_tarif'] + $param->comission; ?> €</span></h1> <?php ECHO $team; ?><br />
                                <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="text-align: right; padding: 2% 10% 1%;">
                                <a href="feuille_de_tournois.php?tournoi=<?php echo $event["event_id"]; ?>"><button class="btn btn-success btn-grand">Voir</button></a>
                            </div>
                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                    else{
                        ?>
                            <div class="espace-top center" style="margin-top: 3%;">
                                <h2 style="font-size: 20px; margin:0;">Vous ne participez à aucun tournoi pour l'instant</h2>
                                <a href="index.php"><button class="btn btn-success btn-mid espace-top"><span class="glyphicon glyphicon-zoom-in"></span> Trouver un tournoi</button></a>
                            </div>
                        <?php
                    }
                    ?>
                </div>


        </div>

    </div>
</body>

<?php include 'footer.php'; ?>

<script type="text/javascript" src="js/scripts/menu_tournois_matchs.js"></script>

</body>
</html>
<?php
}
?>
