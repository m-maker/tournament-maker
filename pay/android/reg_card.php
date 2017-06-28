<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/06/2017
 * Time: 16:56
 */

include "connect_api.php";

$CardRegistration = new\MangoPay\CardRegistration();
$CardRegistration->Tag = "Carte 1";
$CardRegistration->UserId = $_GET["id"];
$CardRegistration->Currency = "EUR";
$CardRegistration->CardType = "CB_VISA_MASTERCARD";
$card = $mangoPayApi->CardRegistrations->Create($CardRegistration);
echo json_encode(array(
    "accessKey" => $card->AccessKey,
    "baseURL" => $mangoPayApi->Config->BaseUrl,
    "cardPreregistrationId" => $card->Id,
    "cardRegistrationURL" => $card->CardRegistrationURL,
    "cardType" => $card->CardType,
    "clientId" => $mangoPayApi->Config->ClientId,
    "preregistrationData" => $card->PreregistrationData));