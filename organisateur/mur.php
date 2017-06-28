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
    if (empty($leTournoi) || $leTournoi == null || $leTournoi["event_orga_id"] != $_SESSION["id"] && $leTournoi["event_orga2_id"] != $_SESSION["id"])
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
        <h1 id="titre_corps"><?php echo $leTournoi["event_titre"]; ?> > Gerer le mur</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

        <!-- CONTENU DE LA PAGE -->
        <div class="container-fluid" style="padding: 1% 1% 2.5%;">

            <?php $heure_debut = format_heure_minute($leTournoi["event_heure_debut"]);
            $heure_fin = format_heure_minute($leTournoi["event_heure_fin"]);
            $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
            if ($leTournoi["event_prive"] == 1){$color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";}
            $pay = "<span class='rouge'>Refusé</span>";
            if ($leTournoi["event_paiement"] == 1){$pay="<span class='vert'>Accepté</span>";}
            $desc = $leTournoi["event_descriptif"];
            if ($leTournoi["event_descriptif"] == NULL || empty($leTournoi["event_descriptif"]))
                $desc = 'Pas de description.';
            $team = "par équipe";
            if ($leTournoi["event_tarification_equipe"] == 0){$team="par joueur";}
            $date_tournoi = new DateTime($leTournoi["event_date"]);
            $date_tournoi = date_lettres($date_tournoi->format("w-d-m-Y"));

            echo "<div class='titre-liste-tournoi'>
            <span class=\"left\"><a href=\"index.php\"> < </a></span>
            " . $leTournoi["event_titre"] . "<br>
            <p style='font-size: 15px;'>
                <span class=\"glyphicon glyphicon-calendar\"></span> Le <span class=\"bold\">" . $date_tournoi . "</span> de
                <span class=\"bold\">" . $heure_debut . "</span> à <span class=\"bold\">" .$heure_fin . "</span>
            </p>
        </div>";

            ?>

            <div class="conteneur-tournoi" style="border-radius:0;width: 100%;margin:0;padding: 1%;">
                <div class="row">

                    <div class="col-lg-4" style="text-align: left;">
                        <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $leTournoi["lieu_nom"];?></span></p>
                        <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                        <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($leTournoi[0]) . ' / ' . $leTournoi["event_nb_equipes"]; ?></span> équipes inscrites</p>
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
                        <h1 style="margin-top: 1.5%;"><span class="bold"><?php echo $leTournoi["event_tarif"];?> €</span></h1> <?php ECHO $team; ?><br />
                        <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                    </div>

                </div>
            </div>

            <hr style="border-color: white;">

            <div id="mur" style="margin: auto;" class="center">
                <div class="cadre_contenu_fdt">
                    <div id="cont_liste-msg-tournoi">
                        <?php $messages = recupMessagesMur($leTournoi[0]);
                        foreach ($messages as $unMessage) {
                            ?>
                            <div class="msg-cont">
                                <?php
                                echo $unMessage["mur_contenu"];
                                if ($unMessage["mur_membre_id"] == $_SESSION["id"]) {
                                    echo '<span class="delete-msg"><a href="delete_msg.php?type=0&id=' . $unMessage["id"] . '&tournoi=' . $leTournoi[0] . '">X</a></span>';
                                }
                                ?>
                                <div class="sign-msg">
                                    Par <span><?php echo $unMessage["membre_pseudo"]; ?></span> le <span><?php echo $unMessage["mur_date"]; ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <form method="post" id="form-mur" action="../post_msg.php?id=<?php echo $leTournoi[0]; ?>">
                        <textarea class="form-control" placeholder="Votre message..." id="message" name="message" rows="3" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;"></textarea>
                        <button class="btn btn-success btn-grand" style="border-top-left-radius: 0; border-top-right-radius: 0;" name="submit"><span class="glyphicon glyphicon-comment"></span> Poster mon message</button>
                    </form>
                </div>
            </div>

        </div>
</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

    <script>
        $("#form-mur").submit(function (e) {
            e.preventDefault();
            var action = $(this).attr("action");
            var message_cont = $("#message");
            var message = message_cont.val();
            $.post(action, {message:message}, function (data) {
                $('#cont_liste-msg-tournoi').prepend(data);
                message_cont.val("");
            });
        });
    </script>

<?php }else{
    header("Location: index.php");
}