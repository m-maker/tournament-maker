<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 19/05/2017
 * Time: 12:14
 */

include '../conf.php';
include 'connect_api.php';

?>

<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Yellowtail" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/volet.css">
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

        <div id="titre_corps">Informations sur le paiement</div>

        <div class="container">
            <div class="form-mango" style="background: #f5f5f5;">

<?php

$Wallet = unserialize($_SESSION['wallet_mango']);
$User = unserialize($_SESSION["utilisateur_mango"]);
$carteEnreg = unserialize($_SESSION['carte_mango']);
$tournoi = unserialize($_SESSION["tournoi_mango"]);

$req = $db->prepare("SELECT * FROM infos_mango INNER JOIN tournois ON im_id = event_mango WHERE event_id = :id_tournoi");
$req->bindValue(":id_tournoi", $tournoi->event_id, PDO::PARAM_INT);
$req->execute();
$mango_tournoi = $req->fetch();

try {

    // Modification de l'objet CardRegisgtration avec les données renvoyées par l'API
    $cardRegister = $mangoPayApi->CardRegistrations->Get($carteEnreg->Id);
    $cardRegister->RegistrationData = isset($_GET['data']) ? 'data=' . $_GET['data'] : 'errorCode=' . $_GET['errorCode'];
    $updatedCardRegister = $mangoPayApi->CardRegistrations->Update($cardRegister);
    if ($updatedCardRegister->Status != \MangoPay\CardRegistrationStatus::Validated || !isset($updatedCardRegister->CardId))
        die('<div style="color:red;">la carte n\'a pas pu être crée, le paiement n\'a pas été effectué.<div>');

    // Récupération de l'objet Card correspondant à la carte de l'utilisateur
    $card = $mangoPayApi->Cards->Get($updatedCardRegister->CardId);

    //var_dump($card);

    // création d'un objet PayIn CARD DIRECT
    $payIn = new \MangoPay\PayIn();
    $payIn->CreditedWalletId = $Wallet->Id;
    $payIn->AuthorId = $updatedCardRegister->UserId;
    $payIn->DebitedFunds = new \MangoPay\Money();
    $payIn->DebitedFunds->Amount = $_SESSION["montant"];
    $payIn->DebitedFunds->Currency = $Wallet->Currency;
    $payIn->Fees = new \MangoPay\Money();
    $payIn->Tag = "Achat d'une place";
    $payIn->Fees->Amount = 100;
    $payIn->Fees->Currency = "EUR";
    // Paiement par carte -> Type et id de la carte ajoutée
    $payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
    $payIn->PaymentDetails->CardType = $card->CardType;
    $payIn->PaymentDetails->CardId = $card->Id;
    // execution type as DIRECT
    $payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
    $payIn->ExecutionDetails->SecureModeReturnURL = 'http://localhost/pay/payment.php';

    // création du PayIn
    $createdPayIn = $mangoPayApi->PayIns->Create($payIn);

    //var_dump($createdPayIn);

    $req_w = $db->prepare("SELECT * FROM infos_mango WHERE im_id = :id");
    $req_w->bindValue(":id", $mango_tournoi["event_mango"], PDO::PARAM_INT);
    $req_w->execute();
    $mango = $req_w->fetch();

    // Création d'un nouvel objet transfert
    $Transfer = new \MangoPay\Transfer();
    $Transfer->Tag = "Transfert vers le complexe";
    $Transfer->AuthorId = $User->Id;
    $Transfer->CreditedUserId = $mango["im_mango_id"];
    $Transfer->DebitedFunds = new \MangoPay\Money();
    $Transfer->DebitedFunds->Currency = "EUR";
    $Transfer->DebitedFunds->Amount = $_SESSION["montant"] - 100;
    $Transfer->Fees = new \MangoPay\Money();
    $Transfer->Fees->Currency = "EUR";
    $Transfer->Fees->Amount = 0;
    $Transfer->DebitedWalletId = $Wallet->Id;
    $Transfer->CreditedWalletId = $mango["im_wallet_id"];

    //var_dump($Transfer);

    // Création du transfert
    $Transfert = $mangoPayApi->Transfers->Create($Transfer);

    // if created Pay-in object has status SUCCEEDED it's mean that all is fine
    if ($createdPayIn->Status == \MangoPay\PayInStatus::Succeeded) {
        print '<div>
            <h2 class="center green">Le paiement a bien été effectué !</h2>
            <h3 class="center"> Vous êtes désormais inscrit pour le tournoi, les fonds ont été débités de votre carte !</h3>
        </div>';

        $req = $db->prepare("UPDATE equipe_membres SET em_membre_paye = 1, em_pay_id = :id_pay WHERE em_team_id = :team AND em_membre_id = :id");
        $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
        $req->bindValue(":id_pay", $createdPayIn->Id, PDO::PARAM_INT);
        $req->bindValue(":team", $_SESSION["team"], PDO::PARAM_INT);
        $req->execute();
        echo '<a href="../feuille_de_tournois.php?tournoi='.$tournoi->event_id.'"><<< Retour au tournoi</a>';
        $_SESSION["tournoi_mango"] = null;
        $_SESSION["team"] = null;

        $notif = new Notifications("<b>".$_SESSION["pseudo"]."</b> à payé sa place avec succes pour le tournoi <b>".$mango_tournoi['event_titre']."</b> que vous administrez !", $mango_tournoi["event_orga"], date("d-m-Y H:i:s"), "organisateur/paiements.php?tournoi=".$mango_tournoi["event_id"]);
        $notif->addNotif();
    }
    else {
        // if created Pay-in object has status different than SUCCEEDED
        // that occurred error and display error message
        print '<div style="color:red;">'.
            'Le paiement a été effectué avec le code d\'attention suivant : '
            . $createdPayIn->Status . ' (result code: '
            . $createdPayIn->ResultCode . ')'
            .'</div>';
    }

} catch (\MangoPay\Libraries\ResponseException $e) {

    print '<div style="color: red;">'
        .'\MangoPay\ResponseException: Code: '
        . $e->getCode() . '<br/>Message: ' . $e->getMessage()
        .'<br/><br/>Details: '; print_r($e->GetErrorDetails())
    .'</div>';
}

?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
    </body>
    </html>
