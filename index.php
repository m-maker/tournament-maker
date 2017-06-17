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
    <link rel="stylesheet" type="text/css" href="css/page_accueil.css">
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
                    <div class="center">
                        <div class="menu-orga1">
                                <button class="btn btn-success show1 acti1" id="show-prives">
                                    <span class="glyphicon glyphicon-list"></span> Matchs Privés
                                </button>
                                <button class="btn btn-success show1" id="show-publiques">
                                    <span class="glyphicon glyphicon-flag"></span> Matchs/tournois publiques
                                </button>
                        </div>
                    </div>
                    <hr/>
                    <div id="publiques" class="cont1">
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
                    </div>
                    <div id="prives" class="cont1">
                        <div id="post2">
                            <div class="container-fluid center" style="padding: 2%;">
                                <div class="gauche">
                                <p>Selectionnez un département</p>
                                <button id="btn_dpt2" class="btn btn-default center" data-toggle="modal" data-target="#myModal2">
                                    <div id="nom_departement2" > Département  <b class="caret"></b> </div>
                                </button>
                                </div>
                                <hr/>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title" id="myModalLabel2">Département</h2>
                                        </div>
                                        <div class="modal-body">
                                            <div class="liste_departements" id="liste_departements2">
                                                <form id="form_dpt2">
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
                                            <button id="valider2" type="button" class="btn btn-default" data-dismiss="modal">Valider</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="div_input2">
                          <div  class="ligne ">
                            <span class="glyphicon glyphicon-time "></span>
                            <p> Quand veux-tu jouer? </p>
                            <span id="glyph_complexe" class="glyphicon glyphicon-remove-circle"></span>
                          </div><?php 
  if (!isset($id_match)){
    $date_creation = new DateTime();
  }
  else {
    $req_date_creation = $bdd->prepare('SELECT * FROM matchs
   WHERE id = :id_match');
    $req_date_creation->execute(array(
      'id_match' => $id_match
      ));
    $res_date_creation = $req_date_creation->fetch();
    $date_creation = $res_date_creation['datecreation'];
  }
    ?>
  <div id="les_horaires">
  <div id="horaire1" class="horaire">
    <select id="horairej1" name="horairej1">
      <?php 
        $joursem = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
        $mois = array("janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre");

        $date = clone $date_creation;
        $date->format('Y-m-d');

        for ($i=0; $i < 15; $i++) { 
        unset($date);
        $date = clone $date_creation;
        $now = new DateTime; 
        
        $date -> add( new DateInterval('P'.$i.'D'));
                ?> 
                  <option value=<?php echo $date->format('j_n_Y').' ';
                    if ($date <  $now) {
                       echo "class=disable";
                       }?>>
                  <?php 
                    echo $joursem[$date->format('w')].' '.$date->format('d').' '.$mois[$date->format('n')-1];
                  ?> 
                  </option>
                <?php
              }
      ?>
    </select>

    <span> de </span>

    <select id="horairedebut1" name="horairedebut1">
      <?php
      $h1 = new DateTime;
      for ($i=8; $i <= 23; $i++) {
        for ($j=0; $j <=1 ; $j++) { 
          ?> 
            <option value=
             <?php 
               if ($j==0){
                $minutes= "00";
                }
                else{
                  $minutes= "30";
                }
                echo $i.'_'.$minutes;

              ?>
            >
             <?php 
               if ($j==0){
                $minutes= "00";
                }
                else{
                  $minutes= "30";
                }
                echo $i.':'.$minutes; 
              ?>
            </option>
          <?php
         }
      }
      ?>
    </select>

    <span> à </span>

    <select id="horairefin1" name="horairefin1">
      <?php
      for ($i=8; $i <= 24; $i++) {
        for ($j=0; $j <=1 ; $j++) { 
          if ($i == 8 AND $j == 0){
          }
          elseif ($i == 24 AND $j == 1){
          }
          else{
            ?>  
              <option value=
                <?php 
                  if ($j==0){
                    $minutes= "00";
                    }
                    else{
                      $minutes= "30";
                    }
                    echo $i.'_'.$minutes; 
                ?>
              >
                <?php 
                  if ($j==0){
                  $minutes= "00";
                  echo $i.':'.$minutes; 
                  }
                  elseif ($j==1) {
                    $minutes= "30";
                    echo $i.':'.$minutes; 
                  }
                ?>
              </option>
            <?php
          }
        }
      }
      ?>
    </select>
    
    <span> .</span>
    
  <button id="button1" type="button" onclick="supprimer_creneau(1)" >-</button>

  </div>
  </div>
  <button id="button_add" type="button" onclick="ajouter_creneau()" >+</button>

  <script type="text/javascript" >
  function ajouter_creneau() {
    var idhoraire = "horaire" + window.numeroInput;
    var horaire = document.getElementById(idhoraire).cloneNode(true);

    window.numeroInput = window.numeroInput +1;
    var newidhoraire = "horaire"+window.numeroInput;

    horaire.setAttribute('id',newidhoraire);

    document.getElementById("les_horaires").appendChild(horaire);
    var anciennumeroInput = numeroInput -1;

    $("#horaire"+numeroInput + " #horairej"+anciennumeroInput).attr('id', "horairej"+numeroInput);
    $("#horaire"+numeroInput + " #horairej"+numeroInput).attr('name', "horairej"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairej"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairej"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #horairedebut"+anciennumeroInput).attr('id', "horairedebut"+numeroInput);
    $("#horaire"+numeroInput + " #horairedebut"+numeroInput).attr('name', "horairedebut"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairedebut"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairedebut"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #horairefin"+anciennumeroInput).attr('id', "horairefin"+numeroInput);
    $("#horaire"+numeroInput + " #horairefin"+numeroInput).attr('name', "horairefin"+numeroInput);
    var value = $("#horaire"+anciennumeroInput + " #horairefin"+anciennumeroInput).val();
    $("#horaire"+numeroInput + " #horairefin"+numeroInput).val(value);

    $("#horaire"+numeroInput + " #button"+anciennumeroInput).attr('id', "button"+numeroInput);
    $("#horaire"+numeroInput + " #button"+numeroInput).attr('onclick', "supprimer_creneau("+numeroInput+")");

    $("#nb_creneaux").attr('value',numeroInput);
  }

  function supprimer_creneau(numero){
    if (numero != 1){
      $("#horaire"+numero).remove();
    }
  }
</script>

<script type="text/javascript">
  var numeroInput = 1;
  alert(var);
</script>
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
                                <li>
                                    coucou
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

    <div id="div1">
        <div id="div1-1">    
            <!-- <img class="img img-responsive" src="img/image_foot2.jpg"> -->
            <div id="div1_img">

            <h1>Facilite-toi la vie: <br/> utilise un outil pour organiser tes matchs</h1>
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
                <strong>Vous n'êtes pas assez pour faire un match</strong>, rejoignez un de nos <strong>matchs publiques</strong> et jouez venez jouez avec d'autres personnes.
                <br/>
                <br/>
                <strong>Vous êtes une équipe</strong>, nous avons des créneaux spécialement réservés pour les <strong>rencontre d'équipes</strong>. C'est l'occasion de faire un <strong>match</strong> ou un <strong>mini-championnat</strong> en fonction du nombre d'équipes.
                <br>
                <br/>
                Sur RTT* ce sont directement <strong>les complexes qui publient leurs matchs</strong> et <strong>il y en à pour tous le goûts!</strong>
                <br/>
                <br/>
                Ha oui, nous avions oublié, bientôt vous pourrez créer vos propres matchs ;-)
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
            - La possibilité de voire <strong>tous les matchs du son complexe en 5 secondes</strong> (oui c'est votre écran d'accueil quand vous vous connectez haha)
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
            <br>Nous faisons tout notre possible pour que puissiez régulièrement bénéificier des <strong>meilleurs offres</strong> aussi bien <strong>sur les tarifs</strong> que <strong>sur les services</strong></p>
            <hr>
        </div>
    </div>
    <div id="div_equipe" class="row">
        <div class="container-fluid">
            <h2>Notre mission - notre équipe - notre histoire</h2>
            <p>Nous savons ce qu'il en est pour vous mais dans notre équipe, nous avons tous connu un paquet de gélères quand il s'agît de faire un foot en salle.
            <br/>
            <br/>
            Sérieusement, nous sommes au 21ème siècle à l'age d'or du numérique et on arrive même pas à savoir qui sera présent au prochain match...
            <br/>
            <br/>
            Imaginez une app, capable de gérer le match comme facebook gère un événément. C'est à dire une liste des participants, des invitations en deux clics, la possibilité de poster un message sur le mur pour que tout le monde puisse le voir...
            <br/>
            Trop c'est trop, nous n'avons pas pu attendre plus longtemps! 
            <br/>
            <br/>
            Nous avons donc décidé de créer une application à la hauteur des problèmes que rencontre les joueurs et joueuses de foot en salle. 
            <br/>
            Aujourd'hui nous sommes toutes une équipe à travailler sur cette plateforme pour vous la proposé très prochainement.
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