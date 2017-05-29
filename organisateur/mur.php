<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2017
 * Time: 12:55
 */

include '../conf.php';

if (isset($_GET["tournoi"])){
    $id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    if (empty($leTournoi) || $leTournoi == null || $leTournoi->event_orga != $_SESSION["id"] && $leTournoi->event_orga_2 != $_SESSION["id"])
        header("Location: index.php");
?>

<html>

<head>
    <?php include('head.php'); ?>
    <title>Administrer mes tournois</title>
    <link rel="stylesheet" type="text/css" href="css/orga.css">
    <link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
    <link rel="stylesheet" type="text/css" href="../css/feuille_tournoi.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
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
        <h1 id="titre_corps">Accueil</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

            
        <div class="title center bold">
            Messages sur le mur du tournoi
        </div>

        <!-- CONTENU DE LA PAGE -->
        <div class="container" id="container" style="padding: 1% 1% 2.5%;">

            <?php $heure_debut = format_heure_minute($leTournoi->event_heure_debut);
            $heure_fin = format_heure_minute($leTournoi->event_heure_fin);
            $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
            if ($leTournoi->event_prive == 1){$color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";}
            $pay = "<span class='rouge'>Refusé</span>";
            if ($leTournoi->event_paiement == 1){$pay="<span class='vert'>Accepté</span>";}
            $desc = $leTournoi->event_descriptif;
            if ($leTournoi->event_descriptif == NULL || empty($leTournoi->event_descriptif))
                $desc = 'Pas de description.';
            $team = "par équipe";
            if ($leTournoi->event_tarification_equipe == 0){$team="par joueur";}

            echo "<div class='titre-liste-tournoi'>
                    <span class=\"left\"><a href=\"index.php\"> < </a></span>
                    " . $leTournoi->event_titre . "<br>
                    <p style='font-size: 15px;'>
                        <span class=\"glyphicon glyphicon-calendar\"></span> Le <span class=\"bold\">" . $leTournoi->event_date . "</span> de
                        <span class=\"bold\">" . $heure_debut . "</span> à <span class=\"bold\">" .$heure_fin . "</span>
                    </p>
                </div>";

            ?>

            <div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
                <div class="row">

                    <div class="col-lg-4" style="text-align: left;">
                        <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $leTournoi->lieu_nom;?></span></p>
                        <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                        <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($leTournoi->event_id) . ' / ' . $leTournoi->event_nb_equipes; ?></span> équipes inscrites</p>
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
                        <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $leTournoi->event_tarif + $param->comission; ?> €</span></h1> <?php ECHO $team; ?><br />
                        <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                    </div>

                </div>
            </div>

            <hr style="border-color: white;">

            <div id="mur">
                <form method="post" action="post_msg.php?id=<?php echo $leTournoi->event_id; ?>">
                    <textarea class="form-control" placeholder="Votre message..." name="message" rows="3" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;"></textarea>
                    <button class="btn btn-success btn-grand" style="border-top-left-radius: 0; border-top-right-radius: 0;" name="submit">Poster mon message</button>
                </form>
                <?php $messages = recupMessagesMur($leTournoi->event_id);
                foreach ($messages as $unMessage) { ?>
                    <div class="message-cont espace-top">
                        <?php echo $unMessage["mur_contenu"]; ?>
                        <span class="delete-msg"><a href="delete_msg.php?id=<?php echo $unMessage["mur_id"]; ?>&tournoi=<?php echo $leTournoi->event_id; ?>">X</a></span>
                        <div class="sign">
                            Par <span><?php echo $unMessage["membre_pseudo"]; ?></span> le <span><?php echo $unMessage["mur_date"]; ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>


        </div>
</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<?php }else{
    header("Location: index.php");
}