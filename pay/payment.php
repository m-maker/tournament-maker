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
        <?php include ('head.php'); ?>
        <title>Tournoi</title>
    </head>

    <body>

        <div class="container">
            <div class="form-mango">

<?php

$Wallet = unserialize($_SESSION['wallet_mango']);
$User = unserialize($_SESSION["utilisateur_mango"]);
$carteEnreg = unserialize($_SESSION['carte_mango']);

try {

    // Modification de l'objet CardRegisgtration avec les données renvoyées par l'API
    $cardRegister = $mangoPayApi->CardRegistrations->Get($carteEnreg->Id);
    $cardRegister->RegistrationData = isset($_GET['data']) ? 'data=' . $_GET['data'] : 'errorCode=' . $_GET['errorCode'];
    $updatedCardRegister = $mangoPayApi->CardRegistrations->Update($cardRegister);
    if ($updatedCardRegister->Status != \MangoPay\CardRegistrationStatus::Validated || !isset($updatedCardRegister->CardId))
        die('<div style="color:red;">la carte n\'a pas pu être crée, le paiement n\'a pas été effectué.<div>');

    // Récupération de l'objet Card correspondant à la carte de l'utilisateur
    $card = $mangoPayApi->Cards->Get($updatedCardRegister->CardId);

    var_dump($card);

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
    $payIn->ExecutionDetails->SecureModeReturnURL = 'http://test.com';

    // création du PayIn
    $createdPayIn = $mangoPayApi->PayIns->Create($payIn);

    // Création d'un nouvel objet transfert
    $Transfer = new \MangoPay\Transfer();
    $Transfer->Tag = "Transfert vers le complexe";
    $Transfer->AuthorId = $User->Id;
    $Transfer->CreditedUserId = "25802911";
    $Transfer->DebitedFunds = new \MangoPay\Money();
    $Transfer->DebitedFunds->Currency = "EUR";
    $Transfer->DebitedFunds->Amount = $_SESSION["montant"] - 100;
    $Transfer->Fees = new \MangoPay\Money();
    $Transfer->Fees->Currency = "EUR";
    $Transfer->Fees->Amount = 0;
    $Transfer->DebitedWalletId = $Wallet->Id;
    $Transfer->CreditedWalletId = "25802914";

    // Création du transfert
    $Transfert = $mangoPayApi->Transfers->Create($Transfer);

    // if created Pay-in object has status SUCCEEDED it's mean that all is fine
    if ($createdPayIn->Status == \MangoPay\PayInStatus::Succeeded) {
        print '<div style="color:green;">'.
            '<h2 class="center">Le paiement a bien été effectué.<br /> '
            .'Pay-In Id = ' . $createdPayIn->Id
            . '<br />, Wallet Id = ' . $Wallet->Id
            . '</h2></div>';
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
    </body>
    </html>
