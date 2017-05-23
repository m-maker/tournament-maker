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

$im = recupImByMembreID($id_membre);
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
            .cont-achat a {
                color: dimgray;
            }
            .cont-achat a:hover {
                color: darkgrey;
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
        $id_wallet =$im['im_wallet_id'];
        $wallet = $mangoPayApi->Wallets->Get($id_wallet);
        $transacs = $mangoPayApi->Wallets->GetTransactions($mango_tournoi["im_wallet_id"]); ?>

        <div class="cont-achat">
            Votre solde : <?php $wallet->Balance->Amount; ?> €
        </div>
        <div class="cont-achat">
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
                <?php foreach ($transacs as $uneTransac) {
                    if ($uneTransac->DebitedWalletId != null) {
                        $joueur = recupImByMangoID($uneTransac->AuthorId);
                        $equipe = recupEquipeJoueur($joueur['membre_id'], $id_tournoi);
                        if ($uneTransac->Status == "SUCCEEDED") {
                            $class = 'success';
                            $statut = '<span class="vert"> Paiement réussi </span>';
                        } else {
                            $class = 'danger';
                            $statut = '<span class="vert"> Paiement échoué </span>';
                        }
                        ?>

                        <tr class="<?php echo $class; ?>">
                            <td class="no-button"><?php echo $uneTransac->Id; ?></td>
                            <td class="no-button"><?php echo $joueur["membre_pseudo"]; ?></td>
                            <td class="no-button"><?php echo $joueur["membre_mail"]; ?></td>
                            <td class="no-button">Le <?php echo date('d/m/Y', $uneTransac->CreationDate); ?>
                                à <?php echo date('H:i:s', $uneTransac->CreationDate); ?></td>
                            <td class="no-button"><?php echo $statut; ?></td>
                            <td class="no-button"><?php echo format_prix($uneTransac->CreditedFunds->Amount); ?> €</td>
                            <td>
                                <button class="btn btn-danger">Rembourser</button>
                            </td>
                        </tr>
                    <?php }
                }?>
                </tbody>
            </table>
        </div>


    <?php }else{ ?>

        <div class="cont-achat">
            <h3 style="margin-top: 0;">Vous n'avez pas encore de compte <strong>MangoPay</strong>,</h3>
            <a href="creer_compte_mango.php">Créez en un gratuitement</a> afin de le relier à votre compte <?php echo $param->nom_site; ?>, et d'acheter vos places en ligne.
        </div>

    <?php } ?>

    </div>

    <?php include 'footer.php'; ?>

    </body>
    </html>
