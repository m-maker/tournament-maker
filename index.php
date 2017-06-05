<?php
include('conf.php');

$ip = $_SERVER['REMOTE_ADDR'];
$fichier_log = 'log/visites.txt';
$fichier_compte = 'log/visites_compte.txt';
$pointeur = fopen($fichier_log, 'a+');
$pointeur_compte = fopen($fichier_compte, 'w+');

$visites = file($fichier_log);
$ecrire = true;
foreach ($visites as $uneVisite){
    if ($ip."\r\n" == $uneVisite)
        $ecrire = false;
}

if ($ecrire) {
    fwrite($pointeur, $ip . "\r\n");
    $compte_visites = count(file($fichier_log));
    fwrite($pointeur_compte, $compte_visites);
}

if (isset($_SESSION["id"]) && $_SESSION['membre_orga'] == 1){
    header('location:organisateur/index.php');
    exit();
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
    <link rel="stylesheet" type="text/css" href="css/index.css">
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
        <!-- CADRE DU CONTENU -->
        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
        <?php
            if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
                    $req = $db->prepare("SELECT membre_dpt_code FROM membres WHERE membre_id = :id");
                    $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
                    $req->execute();
                    $dpt_user = $req->fetchColumn();
                    if ($dpt_user != null){
                        echo '<script>
                            $.post("index_ajax2.php", {dpt:'.$dpt_user.'}, function(data) {
                              $("#post").html(data);
                            });
                        </script>';
                    }
                    ?>
                    <h1 id="titre_corps">Trouver des tournois</h1>

                    <div class="center espace-bot" id="post-avis">
                        <span id="quit-avis" class="right rouge">X</span>
                        <h3>Le site est actuellement en béta ! Aidez-nous à améliorer la plateforme en donnant votre avis : </h3>
                        <form id="form-avis" class="form-horizontal">
                            <div class="form-group">
                                <textarea id="avis" class="form-control btn-grand" placeholder="Votre avis..."></textarea>
                                <button type="submit" class="form-control btn btn-success btn-grand">Envoyer</button>
                            </div>
                        </form>
                        <b>Les paiements en ligne seront disponibles dès mardi 06 juin.</b>
                    </div>

                        <div id="post" class="container-fluid center" style="padding: 2%;">
                            <p style="font-size: 20px;">Selectionnez un département afin de trouver les tournois / matchs</p>
                            <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                                <div id="nom_departement" > Département  <b class="caret"></b> </div>
                            </button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="myModalLabel">Département</h2>
                                    </div>
                                    <div class="modal-body">
                                        <div class="liste_departements" id="liste_departements">
                                            <form id="form_dpt">
                                                <ul>
                                                    <?php
                                                    foreach (listeDepartements() as $key) {
                                                        ?>
                                                        <li>
                                                            <label> <?php echo '('.$key['dpt_code'].') '.$key['dpt_nom']; ?>
                                                                <input type="radio" name="dpt" value="<?php echo $key['dpt_code'] ?>" class="badgebox">
                                                            </label>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="valider" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
            }
            else{
                    ?>
                <div class="conteneur-index center">
                    <div class="opaciteur">
                            <!--<div class="center info-index" style="padding: 1%;">
                                <strong>Hey !</strong> T'es chaud pour faire un foot en salle? <br/> Reste pas sur la page d'accueil et <strong>rejoins nous!</strong>
                            </div>-->
                    <!--<img src="img/logo.png" width="213" height="50" style="margin: auto;"><br />-->
                    <h1 class="nom-site">
                        Reserve Ton Terrain<br />
                    </h1>

                    <h3 class="desc-site">
                        Trouve des équipiers et amuse toi dans les principaux complexes de France
                    </h3>



                    <div id="contenu_corps">

                            <div id="connexion" style="background: rgba(236,240,241,1); margin-top: 20px;">
                                <form class="form-horizontal" id="form-connexion" method="post" action="connexion_check.php">
                                    <fieldset>
                                        <legend class="center">Se connecter</legend>
                                        <div id="erreur-co"></div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="pseudo-inp" name="pseudo" placeholder="Votre pseudo/adresse-mail">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="pass-inp" name="pass" placeholder="*******">
                                        </div>
                                        <input style="display: none;" id="return" name="return" type="text" value="<?php if (isset($_GET["return"])){ echo $_GET['return']; }?>">
                                        <div class="form-group center">
                                            <button type="submit" name="submit" class="btn btn-success" style="width: 80%;">Se connecter</button>
                                        </div>
                                    </fieldset><br /><br />
                                    <a href="recup_pass.php">Mot de passe oublié ?</a>
                                </form>
                            </div>
                            <div id="inscription" style="background: rgba(236,240,241,1); margin-top: 20px;">
                                <form class="form-horizontal" method="post" id="form-inscription" action="inscription_check.php<?php if (isset($_GET['return'])){echo "?return=".$_GET['return'];} ?>">
                                    <fieldset>
                                        <legend class="center">Créez un compte</legend>
                                        <div id="erreur-insc"></div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre pseudo">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="inputTel" name="tel" placeholder="Votre numéro de telephone" pattern="^[0-9]{10}$">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
                                        </div>
                                        <div class="form-group center">
                                            <button type="submit" name="submit" class="btn btn-success" style="width: 80%;">S'inscrire</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            </div>
                    </div>
                        </div>

                </div>
                    <?php
            }
        ?>            
        <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
    </div>
    <script type="text/javascript" src="js/index.js"></script>
</div>
<!-- FOOTER -->
<?php if (isset($_SESSION["id"])){
    include('footer.php');
}
?>
</body>

</html>