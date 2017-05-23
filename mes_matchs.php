<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 20/05/2017
 * Time: 12:30
 */

include 'conf.php';


if (!isset($_SESSION['id']))
    header("Location: connexion.php");

$liste_tournois = liste_tournois_membres($_SESSION["id"]);
//var_dump($liste_tournois);
?>

<html>

<head>
    <?php include 'head.php'; ?>
    <title>Les tournois / matchs auquels je suis inscript</title>
    <link rel="stylesheet" href="css/liste_tournois.css">
</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div class="row espace-top espace-bot">
    <div class="container white" id="container" style="padding-top:0;">

        <div class="row menu-orga espace-bot">
            <div class="col-md-6 center show act" id="show-tournois"><span class="glyphicon glyphicon-list-alt"></span> Mes Tournois</div>
            <div class="col-md-6 center show" id="show-matchs"><span class="glyphicon glyphicon-list"></span> Mes Matchs</div>
        </div>

        <!-- AFFICHAGE DES TOURNOIS -->
        <div class="cont espace-bot" id="tournois">

            <?php
            if (!empty($liste_tournois) && $liste_tournois[0] != null){
                foreach ($liste_tournois AS $un_tournoi){
                    $heure_debut = format_heure_minute($un_tournoi['event_heure_debut']);
                    $heure_fin = format_heure_minute($un_tournoi['event_heure_fin']);
                    $glyph = "glyphicon-eye-open";$prive="Public";$color='vert';
                    if ($un_tournoi['event_prive'] == 1){$color='rouge';$glyph = "glyphicon-eye-close";$prive="Privé";}
                    $pay = "<span class='rouge'>Refusé</span>";
                    if ($un_tournoi['event_paiement'] == 1){$pay="<span class='vert'>Accepté</span>";}
                    $desc = $un_tournoi['event_descriptif'];
                    if ($un_tournoi['event_descriptif'] == NULL || empty($un_tournoi['event_descriptif']))
                        $desc = 'Pas de description.';
                    $team = "par équipe";
                    if ($un_tournoi['event_tarification_equipe'] == 0){$team="par joueur";}
                    ?>
                    <div class="conteneur-tournoi">
                        <a href="feuille_de_tournois.php?tournoi=<?php echo $un_tournoi["event_id"]; ?>">
                            <div class="header-tournoi col-sm-12">
                                <?php echo $un_tournoi['event_titre']; ?>
                            </div>
                            <div class="row">
                                <div class="logo_tournoi col-lg-2">
                                    <img class="img-responsive img-circle" height="50" src="img/logo-tournois/<?php echo $un_tournoi['event_img']; ?>" alt="Tournoi">
                                </div>
                                <div class="col-lg-3">
                                    <p><span class="glyphicon glyphicon-home"></span> Nom du complexe : <span class="bold"><?php echo $un_tournoi['lieu_nom'];?></span></p>
                                    <p><span class="glyphicon glyphicon-euro"></span> Paiement en ligne : <span class="bold"> <?php echo $pay; ?></span></p>
                                    <p><span class="glyphicon glyphicon-user"></span><span class="bold"> <?php echo compte_equipes($un_tournoi['event_id']) . ' / ' . $un_tournoi['event_nb_equipes']; ?></span> équipes inscrites</p>
                                </div>
                                <div class="col-lg-2">
                                    <p><span class="glyphicon glyphicon-calendar"></span> <span class="bold"><?php echo $un_tournoi['event_date'];?></span></p>
                                    <p><span class="glyphicon glyphicon-time"></span> <span class="bold"><?php echo $heure_debut.' - '.$heure_fin; ?></span></p>
                                    <p class="<?php echo $color; ?>"><span class="glyphicon <?php echo $glyph; ?>"></span> Tournoi <?php echo $prive; ?></p>
                                </div>
                                <div class="col-lg-3">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <?php
                                    if (strlen($desc) > 90) {
                                        echo substr($desc, 0, 90)  . '...';
                                    }else{
                                        echo $desc;
                                    } ?>
                                </div>
                                <div class="col-lg-2 prix-team">
                                    <h1><span class="bold"><?php echo $un_tournoi['event_tarif'] + $param->comission; ?> €</span></h1> <?php echo $team; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            }else{ ?>
                <div class="center">
                    <h2 class="white">Vous ne participez à aucun tournoi pour l'instant</h2>
                    <a href="index.php"><button class="btn btn-success btn-mid espace-top"><span class="glyphicon glyphicon-zoom-in"></span> Trouver un tournoi</button></a>
                </div>
            <?php } ?>

        </div>

        <!-- AFFICHAGE DES MATCHS -->
        <div class="cont espace-bot" id="matchs" style="display: none;">

            <h2 class="center espace-bot espace-top">Cette fonctionnalité n'est pas encore disponible</h2>

        </div>


    </div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript" src="js/scripts/menu_tournois_matchs.js"></script>

</body>
</html>
