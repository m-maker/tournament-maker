<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/06/2017
 * Time: 17:58
 */

include "../conf.php";
include "connect_api.php";

$walletId = htmlspecialchars(trim($_POST["walletId"]));
$cardId = htmlspecialchars(trim($_POST["cardId"]));
$cardType = htmlspecialchars(trim($_POST["cardType"]));
$authorId = htmlspecialchars(trim($_POST["authorId"]));
$amount = htmlspecialchars(trim($_POST["amount"]));
$userId = htmlspecialchars(trim($_POST["userId"]));
$teamId = htmlspecialchars(trim($_POST["teamId"]));

$montant =  explode(".", $amount);
if (isset($montant[1])){
    if (strlen($montant[1]) < 2)
        $montant = $montant[0] . $montant[1].'0';
    else
        $montant = $montant[0] . $montant[1];
}else{
    $montant = $montant[0] . '00';
}

function getComission($montant){
    return $montant * 0.15;
}

// création d'un objet PayIn CARD DIRECT
$payIn = new \MangoPay\PayIn();
$payIn->CreditedWalletId = $walletId;
$payIn->AuthorId = $authorId;
$payIn->DebitedFunds = new \MangoPay\Money();
$payIn->DebitedFunds->Amount = $montant - getComission($montant);
$payIn->DebitedFunds->Currency = "EUR";
$payIn->Fees = new \MangoPay\Money();
$payIn->Tag = "Achat d'une place";
$payIn->Fees->Amount = getComission($montant);
$payIn->Fees->Currency = "EUR";
// Paiement par carte -> Type et id de la carte ajoutée
$payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
$payIn->PaymentDetails->CardType = $cardType;
$payIn->PaymentDetails->CardId = $cardId;
// execution type as DIRECT
$payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
$payIn->ExecutionDetails->SecureModeReturnURL = 'http://localhost/pay/payment.php';

// création du PayIn
$createdPayIn = $mangoPayApi->PayIns->Create($payIn);

if ($createdPayIn->Status == "SUCCEEDED"){
    $req = $db->prepare("UPDATE equipe_membres SET em_membre_paye = 1, em_pay_id = :id_pay WHERE em_team_id = :team AND em_membre_id = :id");
    $req->bindValue(":id", $userId, PDO::PARAM_INT);
    $req->bindValue(":id_pay", $createdPayIn->Id, PDO::PARAM_INT);
    $req->bindValue(":team", $teamId, PDO::PARAM_INT);
    $req->execute();
}

echo json_encode($createdPayIn);