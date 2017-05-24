<?php
include('conf.php');
?>
<html>

<head>
    <?php include('head.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/liste_tournois.css">
    <script type="text/javascript" src="js/index.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Tournois de foot en salle</title>
</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->

<!-- BARRE DE RECHERCHE -->
<div class="alert alert-dismissible alert-info" style="width: 70%;margin: 2% auto;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Hey !</strong> Vous êtes un joueur de foot en salle? vous cherchez des tournois ? <h3><a href="inscription.php">Créez votre compte sur RTT</a></h3>
</div>

<div id="post" class="container-fluid center" style="padding: 2%;">
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

<!-- FOOTER -->
<?php include('footer.php') ?>
</body>

</html>