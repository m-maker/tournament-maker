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

    if (empty($leTournoi) || $leTournoi == null || $leTournoi["event_orga_id"] != $_SESSION["id"])
        header("Location: index.php");
    $req = $db->prepare("SELECT * FROM infos_mango INNER JOIN evenements ON infos_mango.id = evenements.event_mango_id WHERE evenements.id = :id_tournoi");
    $req->bindValue(":id_tournoi", $id_tournoi, PDO::PARAM_INT);
    $req->execute();
    $mango_tournoi = $req->fetch();
    $p1 = null; $p2 = null;
    $sort = new \MangoPay\Sorting();
    $sort->AddField("CreationDate", "DESC");
    $transacs = $mangoPayApi->Wallets->GetTransactions($mango_tournoi["im_wallet_id"], $p1, $p2, $sort);
    $wallet = $mangoPayApi->Wallets->Get($mango_tournoi["im_wallet_id"]);
    $cagnotte = format_prix($wallet->Balance->Amount);
    //var_dump($transacs );
    ?>

    <html>

    <head>
        <?php include('head.php'); ?>
        <title>Suivi des paiements</title>
        <link rel="stylesheet" type="text/css" href="css/orga.css">
        <link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
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
        <h1 id="titre_corps"><?php echo $leTournoi["event_titre"]; ?> > Suivi des paiements</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

    <!-- CONTENU DE LA PAGE -->
    <div class="container-fluid" style="padding: 2% 2% 1%;">

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

        <div class="espace-top" style="background: lightgrey; border: 1px solid darkslateblue; color: black; padding: 1%; border-radius: 5px;">
            <div class="ligne">
                <div class="col-lg-10">
                    <h3 style="margin:1%;">Total des fonds récoltés : <span class="bold"><?php echo $cagnotte; ?> €</span></h3>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-success disabled">Verser sur mon compte</button>
                </div>
            </div>
        </div>

        <table class="table table-striped table-hover espace-top" style="border: 1px solid black;">
            <thead class="white head-tab">
                <tr>
                    <th>ID</th>
                    <th>Joueur</th>
                    <th>E-mail</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $liste_rembourses = [];
                foreach ($transacs as $uneTransac) {
                    if ($uneTransac->DebitedWalletId != null) {
                        $req_joueur = $db->prepare('Select * FROM membres INNER JOIN infos_mango ON membre_id = im_membre_id WHERE im_mango_id = :id_user');
                        $req_joueur->bindValue(":id_user", $uneTransac->AuthorId, PDO::PARAM_INT);
                        $req_joueur->execute();
                        $joueur = $req_joueur->fetch();
                        $prix = format_prix($uneTransac->CreditedFunds->Amount);
                        $equipe = recupEquipeJoueur($joueur['membre_id'], $id_tournoi);
                        //var_dump($uneTransac);
                        if ($uneTransac->Nature == "REFUND") {
                            $liste_rembourses[] = $uneTransac->AuthorId;
                            $type = "<span class=\"rouge\">Remboursement</span>"; $prix = "- ".$prix;
                        }else {
                            $type = '<span class="vert">Paiement</span>'; $prix = "+ ".$prix;
                        }
                        if ($uneTransac->Status == "SUCCEEDED") {
                            $class = 'success';
                        } else {
                            $class = 'danger';
                        }
                        ?>

                        <tr class="<?php echo $class; ?>">
                            <td class="no-button"><?php echo $uneTransac->Id; ?></td>
                            <td class="no-button"><?php echo $joueur["membre_pseudo"]; ?></td>
                            <td class="no-button"><?php echo $joueur["membre_mail"]; ?></td>
                            <td class="no-button">Le <?php echo date('d/m/Y', $uneTransac->CreationDate); ?>
                                à <?php echo date('H:i:s', $uneTransac->CreationDate); ?></td>
                            <td><?php echo $type; ?></td>
                            <td class="no-button"><?php echo $prix; ?> €</td>
                            <td>
                                <a href="rembourser.php?tournoi=<?php echo $id_tournoi; ?>&transfert=<?php echo $uneTransac->Id; ?>&membre=<?php echo $joueur["membre_id"]; ?>"
                                <?php if ($uneTransac->Nature == "REGULAR" && !in_array($uneTransac->AuthorId, $liste_rembourses)){ ?> <button class="btn btn-danger">Rembourser</button> <?php } ?>
                                </a>
                            </td>
                        </tr>
                    <?php }
                }?>
            </tbody>
        </table>
    </div>
    </div>
</div>

    <!-- FOOTER -->
    <?php include 'footer.php'; ?>

    </body>
    </html>

<?php }else{
    header("Location: index.php");
} ?>