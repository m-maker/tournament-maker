<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 22/05/2017
 * Time: 16:18
 */

include '../conf.php';
include 'connect_api.php';

function format_prix($montant){
    $entier = substr($montant, 0, -2);
    $decimales = substr($montant, strlen($entier));
    return $cagnotte = $entier . "." . $decimales;
}

if (isset($_GET["tournoi"])) {
    $id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);
    //var_dump($leTournoi);
    if (empty($leTournoi) || $leTournoi == null || $leTournoi->event_orga != $_SESSION["id"])
        header("Location: index.php");
    $req = $db->prepare("SELECT * FROM infos_mango INNER JOIN tournois ON im_id = event_mango WHERE event_id = :id_tournoi");
    $req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
    $req->execute();
    $mango_tournoi = $req->fetch();
    $transacs = $mangoPayApi->Wallets->GetTransactions($mango_tournoi["im_wallet_id"]);//var_dump($transacs);
    $wallet = $mangoPayApi->Wallets->Get($mango_tournoi["im_wallet_id"]);
    $cagnotte = format_prix($wallet->Balance->Amount);
    ?>

    <html>

    <head>
        <?php include('head.php'); ?>
        <title>Administrer mes tournois</title>
        <link rel="stylesheet" type="text/css" href="css/orga.css">
        <link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    </head>

    <body>

    <!-- HEADER -->
    <?php include('header.php'); ?>

    <div class="title center bold">Gérer mes paiements</div>

    <!-- CONTENU DE LA PAGE -->
    <div class="container" id="container" style="padding: 2% 2% 1%; margin: 3% auto;">

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

        <div class="espace-top" style="background: white; color: black; padding: 1%; border-radius: 5px;">
            <h3 style="margin:0;">Total des fonds récoltés : <span class="bold"><?php echo $cagnotte; ?> €</span></h3>
        </div>

        <table class="table table-striped table-hover espace-top">
            <thead class="white head-tab">
                <tr>
                    <th>ID de transaction</th>
                    <th>Joueur</th>
                    <th>E-mail</th>
                    <th>Date</th>
                    <th>Etat</th>
                    <th>Prix</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transacs as $uneTransac){
                    $req_joueur = $db->prepare('Select * FROM membres INNER JOIN infos_mango ON membre_id = im_membre_id WHERE im_mango_id = :id_user');
                    $req_joueur->bindValue(":id_user", $uneTransac->AuthorId, PDO::PARAM_INT);
                    $req_joueur->execute();
                    $joueur = $req_joueur->fetch();
                    $equipe = recupEquipeJoueur($joueur['membre_id'], $id_tournoi);
                    if ($uneTransac->Status == "SUCCEEDED") {
                        $class = 'success';
                        $statut = '<span class="vert"> Paiement réussi </span>';
                    }else {
                        $class = 'danger';
                        $statut = '<span class="vert"> Paiement échoué </span>';
                    }
                    ?>

                    <tr class="<?php echo $class; ?>">
                        <td class="no-button"><?php echo $uneTransac->Id; ?></td>
                        <td class="no-button"><?php echo $joueur["membre_pseudo"]; ?></td>
                        <td class="no-button"><?php echo $joueur["membre_mail"]; ?></td>
                        <td class="no-button">Le <?php echo date('d/m/Y', $uneTransac->CreationDate); ?> à <?php echo date('H:i:s', $uneTransac->CreationDate); ?></td>
                        <td class="no-button"><?php echo format_prix($uneTransac->CreditedFunds->Amount); ?> €</td>
                        <td class="no-button"><?php echo $statut; ?></td>
                        <td><button class="btn btn-danger">Rembourser</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    </body>
    </html>

<?php }else{
    header("Location: index.php");
} ?>