<?php
include('conf.php');
?>
<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script type="text/javascript" src="js/index.js"></script>
    <title>Tournois de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <h1 id="titre_corps">Accueil</h1>
        <!-- CADRE DU CONTENU -->
        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
        <?php
            if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
                if ($_SESSION['membre_orga'] == 1){
                    header('location:organisateur/index.php');
                }
                else {
                    ?>
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
            }
            else{
                    ?>
                        <div class="center info-index" style="padding: 1%;">
                            <strong>Hey !</strong> T'es chaud pour faire un foot en salle? <br/> <br/> Reste pas sur la page d'accueil et <strong>rejoins nous!</strong>
                        </div>
                        <div id="contenu_corps">
                            <div id="connexion">
                                <form class="form-horizontal" method="post" action="connexion_check.php">
                                    <fieldset>
                                        <legend class="center">Se connecter</legend>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre pseudo/adresse-mail">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
                                        </div>
                                        <div class="form-group center">
                                            <button type="submit" name="submit" class="btn btn-success" style="width: 80%;">Se connecter</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div id="inscription">
                                <form class="form-horizontal" method="post" action="inscription_check.php">
                                    <fieldset>
                                        <legend class="center">Créez un compte :</legend>
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
                    <?php
            }
        ?>            
        <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
    </div>
</div>
<!-- FOOTER -->
<?php include('footer.php') ?>
</body>

</html>