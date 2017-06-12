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
                            <div class="gauche">
                            <p style="font-size: 20px;">Selectionnez un département afin de trouver les tournois / matchs</p>
                            <button id="btn_dpt" class="btn btn-default center" data-toggle="modal" data-target="#myModal">
                                <div id="nom_departement" > Département  <b class="caret"></b> </div>
                            </button>
                            </div>
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