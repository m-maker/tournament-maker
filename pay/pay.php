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
var_dump($User);
$usernat = $mangoPayApi->Users->Create($User);

// Création du porte-monnaie (wallet de cet utilisateur)
$Wallet = new \MangoPay\Wallet();
$Wallet->Owners = array($usernat->Id);
$Wallet->Description = "Demo wallet for User 1";
$Wallet->Currency = "EUR";
$wallt = $mangoPayApi->Wallets->Create($Wallet);
//var_dump($result);
// Création d'un utilisateur légal (Buisness)
/*$User = new MangoPay\UserLegal();
$User->Name = "Huctin Damien";
$User->LegalPersonType = "BUSINESS";
$User->Email = "damien.huctin@gmail.com";
$User->LegalRepresentativeFirstName = "Damien";
$User->LegalRepresentativeLastName = "Huctin";
$User->LegalRepresentativeBirthday = 110496;
$User->LegalRepresentativeNationality = "FR";
$User->LegalRepresentativeCountryOfResidence = "ZA";
$legal = $mangoPayApi->Users->Create($User);
// Création du porte monnaie de l'utilisateur légal
$Wallet = new \MangoPay\Wallet();
$Wallet->Owners = array($legal);
$Wallet->Description = "Demo wallet for User 2";
$Wallet->Currency = "EUR";
$wallt = $mangoPayApi->Wallets->Create($Wallet);
*/
$PayIn = new \MangoPay\PayIn();
$PayIn->CreditedWalletId = $wallt->Id;
$PayIn->AuthorId = $usernat;
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
//$PayIn->ExecutionDetails->ReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?stepId=".($stepId+1);
$PayIn->ExecutionDetails->Culture = "EN";
$result = $mangoPayApi->PayIns->Create($PayIn);
var_dump($result);

//$result = $mangoPayApi->PayIns->Get($_SESSION["MangoPayDemo"]["PayInCardWeb"]); */

?>
