<?php
include('conf.php');
include "connect_api_fb.php";

$ip = $_SERVER['REMOTE_ADDR'];
$fichier_log = 'log/visites.txt';
$fichier_compte = 'log/visites_compte.txt';
$pointeur = fopen($fichier_log, 'a+');
$pointeur_compte = fopen($fichier_compte, 'w+');

$visites = file($fichier_log);
$ecrire = true;
foreach ($visites as $uneVisite){
    if ($ip."\r\n" == $uneVisite){
        $ecrire = false;
      }
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
    <link rel="stylesheet" type="text/css" href="css/page_accueil.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Tournois de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php if (isset($_SESSION["id"])){
    include('header.php');
    var_dump($_SESSION['membre_orga']);
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
                    $req = $db->prepare("SELECT membre_dpt_code FROM membres WHERE id = :id");
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
                    <h1 id="titre_corps">Trouver des matchs</h1>
                        <div id="post" class="container-fluid center" style="padding: 2%;">
                            <p style="color: black; font-size: 20px;">Selectionnez un département afin de trouver les tournois / matchs</p>
                            <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                                <div id="nom_departement" > Département  <b class="caret"></b> </div>
                            </button>
                            <hr/>
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
                <nav id="navbar"  class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar">
                            <ul id="ul_nav" class="nav navbar-nav">
                                <li><a href="#div1">Accueil</a></li>
                                <li>
                                    <a href="#div2">La plateforme</a>
                                </li>
                                <li>
                                    <a href="#equipe">l'équipe</a>
                                </li>
                                <li>
                                    <a href="#com">On parle de nous</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

    <div id="div1">
        <div id="div1-1" class="col-sm-12 col-md-8">    
            <!-- <img class="img img-responsive" src="img/image_foot2.jpg"> -->
            <div id="div1_img">

            <h1 id="titre_image_acceuil" class="col-sm-12 col-md-8">Des tournois et des matchs de foot en salle<br/>Rejoignez-nous, <br/> que vous soyez seul ou en équipe!</h1>
            </div>
        </div>
        <div id="div1-2">
            <div id="connexion">
                <form class="form-horizontal" id="form-connexion" method="post" action="connexion_check.php">
                    <fieldset>
                        <legend class="center">Se connecter</legend>
                        <div id="erreur-co">
                        </div>
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
                    </fieldset>
                    <br />
                    <br />
                    <?php
                                        $permissions = ['email']; // Optional permissions
                                        $loginUrl = $helper->getLoginUrl('https://reservetonterrain.fr/fb-callback.php', $permissions);
                                        echo '<div class="form-group center" style="margin-top: 20px; margin-bottom:20px;">
                                        <a class="espace-bot espace-top" href="' . htmlspecialchars($loginUrl) . '">
                                            <button type="button" class="btn btn-primary" style="width: 80%;">
                                                <img src="img/icones/icone_facebook.ico" width="25" style="margin-top: -5px;" /> Se connecter avec Facebook!
                                            </button>
                                        </a>
                                        </div>'; ?>

                    <a href="recup_pass.php">Mot de passe oublié ?</a>
                </form>
            </div>
            <div id="inscription">
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
                                            <input type="text" class="form-control" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
                                        </div>
                  
                                        <div class="form-group center">
                                            <button type="submit" name="submit" class="btn btn-success" style="width: 80%;">S'inscrire</button>
                                        </div>
                                    </fieldset>
                                </form>
            </div>
        </div>
    </div>

    <div id="div2" class="row">
        <div id="fct_1" class="col-sm-12 col-md-4 fct">
            <div class="fct_img">
                <img class="img img-responsive" src="img/fct_img_1.jpg">
            </div>
            <h2 class="fct_titre">1ère plateforme en diversité de matchs</h2>
            <hr>
            <p>
                <strong>Vous n'êtes pas assez pour faire un match</strong>, rejoignez un de nos <strong>matchs publiques</strong>.
                <br/>
                <br/>
                <strong>Vous êtes une équipe ?</strong>, nous avons des créneaux spécialement réservés pour les <strong>rencontres d'équipes</strong>. C'est l'occasion de faire un <strong>match</strong> ou un <strong>mini-championnat</strong>.
                <br>
                <br/>
                Sur RTT* ce sont directement <strong>les complexes qui publient leurs matchs</strong> et <strong>il y en a pour tous le goûts!</strong>
                <br/>
                <br/>
                Ha oui, nous avons oublié de vous dire que bientôt, vous pourrez créer vos propres matchs ;-)
                <br/>
                *RTT: ReserveTonTerrain.fr
            </p>
            <hr>
        </div>
        <div id="fct_2" class="col-sm-12 col-md-4 fct">
            <div class="fct_img">
                <img class="img img-responsive" src="img/fct_img_2.png">
            </div>
            <h2 class="fct_titre">Une organisation aux petits oignons</h2>
            <hr>
            <p>C'est tellement simple que nous nous risquerons pas à vous l'expliquer.
            <br/>
            <br/>
            Mais comme il est d'usage de mettre <strong>l'eau à la bouche</strong> des personnes qui font l'effort de lire notre page d'accueil, et même si cela sera <strong>difficile à croire</strong> voici quelque'unes des <strong>principales fonctionnalités.</strong>
            <br/>
            <br/>
            - La possibilité de voir <strong>tous les matchs de son complexe en 5 secondes</strong> (oui c'est notre écran d'accueil quand vous vous une fois connecté, allez regarder pour voir.).
            <br/>
            <br/>
            - <strong>L'inscription</strong> à un match se fait en <strong>5 secondes aussi.</strong> (2 clics).
            <br>
            <br/>
            - Une <strong>gestion automatique des participations</strong> avec une <strong>liste d'attente</strong> en cas de desistement.
            <hr>
        </div>
        <!-- 
        <div id="fct_2" class="col-sm-12 col-md-4">
            <div class="fct_img">
                <img class="img img-responsive" src="img/fct_img_2.png">
            </div>
            <h2 class="fct_titre">Une organisation aux petits oignons</h2>
            <hr>
            <p>C'est tellement simple que nous nous risquerons pas à vous l'expliquer. Mais comme il est d'usage de mettre l'eau à la bouche des personnes qui font l'effort de lire notre page d'accueil, et même si cela sera difficile à croie voici quelque'unes de nos principales fonctionnalités.
            <br/>
            - La possibilité d'envoyer à des invitations et des messages à tout le monde (même à l'ami d'un ami avec qui on a joué il y 2 mois, c'est possible!). durée: 5 à 10 secondes selon l'âge!
            <br/>
            - Une gestion automatique des réponses avec une liste d'attente en cas de desistement: durée: 0 secondes.
            <br/>
            - La réservation en ligne en 2 clics: durée: 5 secondes.
            <br/>
            En une minute chrono, vous organisez votre match de A à Z.
            </p>
            <hr>
        </div>
        -->
        <div id="fct_3" class="col-sm-12 col-md-4 fct">
            <div class="fct_img">
                <img class="img img-responsive" src="img/fct_img_3.jpg">
            </div>
            <h2 class="fct_titre">Sans parler de...</h2>
            <hr>
            <p> Nous avons déjà réussi à négocier des avantages pour nos utilisateurs!
            <br>Nous faisons tout notre possible pour que puissiez régulièrement bénéficier des <strong>meilleurs offres</strong> aussi bien <strong>sur les tarifs</strong> que <strong>sur les services</strong></p>
            <hr>
        </div>
    </div>
    <div id="div_equipe" class="row">
        <div class="container-fluid">
            <h2>Notre mission - notre équipe - notre histoire</h2>
            <p>Dans notre équipe, nous avons tous connu un paquet de galères quand il s'agit de faire un foot en salle...
            <br/>
            <br/>
            Nous sommes au 21ème siècle, à l'age d'or du numérique, et nous n'arrivons même pas à savoir qui sera présent au prochain match...
            <br/>
            <br/>
            Imaginez une app' capable de gérer le match comme facebook gère un événément. Avec une liste des participants, des invitations en deux clics, la possibilité de poster un message sur le mur pour que tout le monde puisse le voir...
            <br/>
            Trop c'est trop, nous n'avons pas pu attendre plus longtemps! 
            <br/>
            <br/>
            Nous avons donc décidé de créer une application à la hauteur des problèmes que rencontrent les joueurs et joueuses de foot en salle. 
            <br/>
            Aujourd'hui nous sommes toute une équipe à travailler sur cette plateforme pour vous la proposer très prochainement.
            <br/>
            <br/>
            Pour les curieux, les impatients et nos testeurs que nous remercions, nous avons mis sur ce site une version web de la future application. Pour cela il vous suffit de vous<a href="#div1"> Connecter</a>.
            </p>

            <div id="#equipe">
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>Damien, co-fondateur</p>
                </div>
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>Anthony, co-fondateur</p>
                </div>
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>Loïc, expert développeur</p>
                </div>
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>Yoann, chargé événementiel <br/> et communcation</p>
                </div>
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>Alex, Investisseur</p>
                </div>
                <div class="col-sm-6 col-md-3 membre_equipe">
                    <img class="img img-circle" src="img/damien.jpg">
                    <p>David, Investisseur</p>
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
<script type="text/javascript">
    
            $(".show1").click(function() {
                $(".show1").removeClass("acti1");
                $(this).addClass("acti1");
                $(".cont1").hide();
                var id = $(this).attr("id");
                if (id == "show-prives")
                    $("#prives").show();
                else if (id == "show-publiques")
                    $("#publiques").show();
            });
</script>
</body>

</html>