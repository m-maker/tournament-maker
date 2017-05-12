<?php 

include 'connect_api.php';

// Création d'un utilisateur
$User = new MangoPay\UserNatural();
$User->Email = "test_natural@testmangopay.com";
$User->FirstName = "Bob";
$User->LastName = "Briant";
$User->Birthday = 121271;
$User->Nationality = "FR";
$User->CountryOfResidence = "ZA";
$naturalUserResult = $mangoPayApi->Users->GetNatural(4991600);
var_dump($naturalUserResult);
/*$result = $mangoPayApi->Users->Create($User);
// Création du porte-monnaie (wallet de cet utilisateur)
$Wallet = new \MangoPay\Wallet();
$Wallet->Owners = array($_SESSION["MangoPayDemo"]["UserNatural"]);
$Wallet->Description = "Demo wallet for User 1";
$Wallet->Currency = "EUR";
$result = $mangoPayApi->Wallets->Create($Wallet);

// Création d'un utilisateur légal (Buisness)
$User = new MangoPay\UserLegal();
$User->Name = "Huctin Damien";
$User->LegalPersonType = "BUSINESS";
$User->Email = "damien.huctin@gmail.com";
$User->LegalRepresentativeFirstName = "Damien";
$User->LegalRepresentativeLastName = "Huctin";
$User->LegalRepresentativeBirthday = 110496;
$User->LegalRepresentativeNationality = "FR";
$User->LegalRepresentativeCountryOfResidence = "ZA";
$result = $mangoPayApi->Users->Create($User);
// Création du porte monnaie de l'utilisateur légal
$Wallet = new \MangoPay\Wallet();
$Wallet->Owners = array($_SESSION["MangoPayDemo"]["UserLegal"]);
$Wallet->Description = "Demo wallet for User 2";
$Wallet->Currency = "EUR";
$result = $mangoPayApi->Wallets->Create($Wallet);

//Paiement en CB
/*
$PayIn = new \MangoPay\PayIn();
$PayIn->CreditedWalletId = $_SESSION["MangoPayDemo"]["WalletForNaturalUser"];
$PayIn->AuthorId = $_SESSION["MangoPayDemo"]["UserNatural"];
$PayIn->PaymentType = "CARD";
$PayIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
$PayIn->PaymentDetails->CardType = "CB_VISA_MASTERCARD";
$PayIn->DebitedFunds = new \MangoPay\Money();
$PayIn->DebitedFunds->Currency = "EUR";
$PayIn->DebitedFunds->Amount = 2500;
$PayIn->Fees = new \MangoPay\Money();
$PayIn->Fees->Currency = "EUR";
$PayIn->Fees->Amount = 150;
$PayIn->ExecutionType = "WEB";
$PayIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsWeb();
$PayIn->ExecutionDetails->ReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?stepId=".($stepId+1);
$PayIn->ExecutionDetails->Culture = "EN";
$result = $mangoPayApi->PayIns->Create($PayIn);

$result = $mangoPayApi->PayIns->Get($_SESSION["MangoPayDemo"]["PayInCardWeb"]);
*/
?>
