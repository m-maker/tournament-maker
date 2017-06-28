<?php

include "../conf.php";
include 'connect_api.php';

if (isset($_GET["tournoi"]) && $_GET["team"]) {
    $tab_dates = array("01" => "Janvier", "02" => "Fevrier", "03" => "Mars", "04" => "Avril", "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Aout", "09" => "Septembre", "10" => "Octobre", "11" => "Novembre", "12" => "Décembre");

    // Cherche si le membre a déja un compte MangoPay
    $req = $db->prepare("SELECT * FROM infos_mango WHERE im_membre_id = :id");
    $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
    $req->execute();
    $membre_mango = $req->fetch();

    $id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
    $team = htmlspecialchars(trim($_GET["team"]));
    $leTournoi = recupObjetTournoiByID($id_tournoi);

    if (!empty($leTournoi)) {

        $montant =  explode(".", $leTournoi->event_tarif);
        if (isset($montant[1])){
            if (strlen($montant[1]) < 2)
                $montant = $montant[0] . $montant[1].'0';
            else
                $montant = $montant[0] . $montant[1];
        }else{
            $montant = $montant[0] . '00';
        }

        $_SESSION["montant"] = $montant;
        $_SESSION["tournoi_mango"] = serialize($leTournoi);
        $_SESSION["team"] = $team;

        // Si il a déja un compte
        if ($req->rowCount() > 1) {
            // on récupère le compte et on redirige
            $user = $mangoPayApi->Users->Get($membre_mango["im_mango_id"]);
            $wallet = $mangoPayApi->Wallets->Get($membre_mango["im_wallet_id"]);
            $_SESSION['utilisateur_mango'] = serialize($user);
            $_SESSION["wallet_mango"] = serialize($wallet);
            header("Location: cartes.php");
        }

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

                <div id="titre_corps">Vos informations d'utilisateur</div>

            <div class="container espace-bot">

            <div class="form-mango">
                <form class="form-horizontal" method="post" action="add_user.php">
                    <fieldset>
                        <legend class="center">Créer votre compte MangoPay</legend>
                        <div class="form-group">
                            <label for="nom" class="col-lg-2 control-label">Nom</label>
                            <div class="col-lg-10">
                                <input type="text" name="nom" class="form-control" placeholder="Nom"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prenom" class="col-lg-2 control-label">Prénom</label>
                            <div class="col-lg-10">
                                <input type="text" name="prenom" class="form-control" placeholder="Prénom"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mail" class="col-lg-2 control-label">E-mail</label>
                            <div class="col-lg-10">
                                <input type="email" name="mail" class="form-control"
                                       placeholder="Adresse de courrier électronique (ex: contact@adn-five.fr)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Date de naissance</label>
                            <div class="col-lg-10">
                                <select class="form-control align-select" name="jour" placeholder="Jour">
                                    <?php for ($i = 1; $i < 31; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                                <select class="form-control align-select" name="mois" placeholder="Mois">
                                    <?php foreach ($tab_dates as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                                <select class="form-control align-select" name="annee" placeholder="Année">
                                    <?php for ($i = date("Y"); $i > date("Y") - 100; $i--) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                              <label for="mail" class="col-lg-2 control-label">Adresse postale</label>
                              <div class="col-lg-10">
                                <input type="text" name="adresse" class="form-control" placeholder="Adresse postale (ex: 2 rue du Petit Muguet)">
                              </div>
                        </div>
                        <div class="form-group">
                              <label for="mail" class="col-lg-2 control-label">Ville</label>
                              <div class="col-lg-10">
                                <input type="text" name="ville" class="form-control" placeholder="Ville de résidence (ex: Bordeaux)">
                              </div>
                        </div>
                        <div class="form-group">
                              <label for="mail" class="col-lg-2 control-label">Code Postal</label>
                              <div class="col-lg-10">
                                <input type="number" name="cp" class="form-control" placeholder="Code postal (ex: 33150)">
                              </div>
                        </div> -->
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Nationnalité</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="nat" placeholder="Année">
                                    <option value="FR">France</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Pays de résidence</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="residence" placeholder="Année">
                                    <option value="FR">France</option>
                                </select>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Devise (Monnaie) utilisée</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="devise" placeholder="Monnaie">
                                    <option value="EUR">Euros</option>
                                    <option value="DOL">Dollars</option>
                                </select>
                            </div>
                        </div>-->
                        <div class="form-group" style="text-align: center;">
                            <button class="btn btn-default align-select" type="reset">Annuler</button>
                            <button class="btn btn-success align-select">Ajouter mon compte</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

        </body>
        </html>

        <?php

    }else{
        header("Location: ../index.php");
    }
}else{
    header("Location: ../index.php");
}
    ?>