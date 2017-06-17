<?php 

include 'connect_api.php';
include '../conf.php';

$User = unserialize($_SESSION["utilisateur_mango"]);
$Wallet = unserialize($_SESSION["wallet_mango"]);

// create pay-in CARD DIRECT
$payIn = new \MangoPay\PayIn();
$payIn->CreditedWalletId = $Wallet->Id;
$payIn->AuthorId = $User->Id;
$payIn->DebitedFunds = new \MangoPay\Money();
$payIn->DebitedFunds->Amount = $_SESSION["montant"];
$payIn->DebitedFunds->Currency = $Wallet->Currency;
$payIn->Fees = new \MangoPay\Money();
$payIn->Fees->Amount = 100;
$payIn->Fees->Currency = "EUR";

// payment type as CARD
$payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
$payIn->PaymentDetails->CardType = $card->CardType;
$payIn->PaymentDetails->CardId = $card->Id;

// execution type as DIRECT
$payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
$payIn->ExecutionDetails->SecureModeReturnURL = 'http://test.com';

// create Pay-In
$createdPayIn = $mangoPayApi->PayIns->Create($payIn);
//var_dump($createdPayIn);

//$Paiement = $mangoPayApi->PayIns->Get($_SESSION["MangoPayDemo"]["PayInCardWeb"]);

?>
