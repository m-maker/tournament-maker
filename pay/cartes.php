<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 19/05/2017
 * Time: 11:25
 */

include '../conf.php';
include 'connect_api.php';

if (!isset($_SESSION["id"]) || $_SESSION["membre_orga"] == 1 || $_SESSION["tournoi_mango"] == null || $_SESSION["team"] == null){
    header("Location: ../index.php");
}

$User =  unserialize($_SESSION["utilisateur_mango"]);
$Wallet = unserialize($_SESSION["wallet_mango"]);


?>

<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Yellowtail" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Mes informations de paiement</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">
    <?php include('volet.php'); ?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">

        <div id="titre_corps">Mes informations de paiement</div>

        <div class="container espace-bot">
            <div class="form-mango">

<?php

if (isset($_POST["cardType"])) {
    try {

        $CardRegistration = new \MangoPay\CardRegistration();
        $CardRegistration->Tag = "Carte de " . $_SESSION["pseudo"];
        $CardRegistration->UserId = $User->Id;
        $CardRegistration->Currency = $Wallet->Currency;
        $CardRegistration->CardType = htmlspecialchars(trim($_POST["cardType"]));

        $cardRegistration = $mangoPayApi->CardRegistrations->Create($CardRegistration);
        $createdCardRegister = $mangoPayApi->CardRegistrations->Get($cardRegistration->Id);

        $returnUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
        $returnUrl .= substr($_SERVER['REQUEST_URI'], 0, strripos($_SERVER['REQUEST_URI'], '/') + 1);
        $returnUrl .= 'payment.php';

        //$_SESSION['montant'] = 4000;
        $_SESSION["carte_mango"] = serialize($createdCardRegister);

        //var_dump($createdCardRegister);

        ?>

                        <h2 class="center bold"><?php print $User->FirstName . ' ' . $User->LastName; ?></h2>

                        <form class="form-horizontal" action="<?php print $createdCardRegister->CardRegistrationURL; ?>"
                              method="post">
                            <fieldset>
                                <legend class="center">Saisissez vos informations de paiement :</legend>

                                <input type="hidden" name="data"
                                       value="<?php print $createdCardRegister->PreregistrationData; ?>"/>
                                <input type="hidden" name="accessKeyRef"
                                       value="<?php print $createdCardRegister->AccessKey; ?>"/>
                                <input type="hidden" name="returnURL" value="<?php print $returnUrl; ?>"/>

                                <div class="form-group">
                                    <label for="cardNumber" class="col-lg-2 control-label">Numero de CB</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" type="text" name="cardNumber"
                                               placeholder="N° de carte (ex: 4706750000000033)" value=""/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cardExpirationDate" class="col-lg-2 control-label">Date
                                        d'expiration</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" type="text" name="cardExpirationDate"
                                               placeholder="Date d'expiration sans le slash (ex: 0518)" value=""/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cardExpirationDate" class="col-lg-2 control-label">Cryptogramme
                                        visuel</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" type="text" name="cardCvx"
                                               placeholder="Cryptogramme (ex: 155)" value=""/>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-grand" value="Pay">Valider le
                                    paiement
                                </button>

                            </fieldset>
                        </form>

                        <h3 class="center">
                            Montant à payer:<br/>
                            <span class="bold">
                            <?php print substr($_SESSION['montant'], 0, -2) . '.' . substr($_SESSION['montant'], -2) . ' ' . $Wallet->Currency; ?></span>
                        </h3>
        <?php
    } catch (MangoPay\Libraries\ResponseException $e) {
// handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails()

    } catch (MangoPay\Libraries\Exception $e) {
// handle/log the exception $e->GetMessage()

    }
}else{ ?>

    <form method="post">
        <label for="cardType">Type de votre carte bancaire :</label>
        <select class="form-control" name="cardType">
            <option value="CB_VISA_MASTERCARD">Visa / MasterCard</option>
            <option value="MAESTRO">Maestro</option>
        </select>
        <button class="form-control btn btn-success">Valider</button>
    </form>

<?php } ?>

            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

