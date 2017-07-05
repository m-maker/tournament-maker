<?php
  // Test de redirection 
include('conf.php');
  if (isset($_SESSION['id']) AND !empty($_SESSION['id'])){
    if (isset($_SESSION['orga']) AND $_SESSION['orga'] == 1){
      header('location:organisateur/home.php');
    }
    else{
      header('location:home.php');
    }
  }
  else{

    // si pas de redirection =>
    ?>
      <!DOCTYPE html>
        <html>
        	<head>
    <?php include('head.php'); ?>
            <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
        		<link rel="stylesheet" type="text/css" href="css/v2/style.css">
        		<link rel="stylesheet" type="text/css" href="css/v2/index.css">
            <link href="https://fonts.googleapis.com/css?family=Rock+Salt" rel="stylesheet">
        		<title>RTT</title>
        	</head>

        	<body>

          <!-- ****************************************         Debut du bandeau        ****************************************  -->

        	<div class="logo">
            <img src="logo.png" alt="RTT">
        	</div>
          <p>
            Sans aucun doute <strong>une révolution</strong> dans l'<strong>organisation</strong> des matchs <strong>foots en salle</strong>
          </p> 
          <div class="container-fluid">
            <div class="connexion col-xs-12 col-sm-6">
              <h1>Connexion</h1>
              <form method="post" action="connexion_check.php">

                <div>
                  <?php if(isset($_GET['erreur'])) {
                      if($_GET['erreur'] =="pseudo") { ?><p><?php echo "Nous n'avons pas encore enregistré "; echo $_GET['pseudo']; echo " chez nous. C'est le moment de s'inscire non?"; ?></p><?php } 
                      }
                  ?>
                  <input class="champ" type="text" name="pseudo" placeholder="Votre pseudo/adresse-mail"/>
                </div>
                <div>
                  <?php if(isset($_GET['erreur'])) {
                      if($_GET['erreur'] =="mdp") { ?> <p> <?php echo "Oups, nous n'avons pas le même mot de passe pour : "; echo $_GET['pseudo'];?><br/><?php echo " Et parce qu'on est cool, on te laisse reessayer ;-)"; ?></p><?php } 
                      } 
                  ?>
                  <input class="champ" type="password" id="pass-inp" name="pass" placeholder="*******"/>
                </div>
                <div>
                <a href="recup_pass.php">Mot de pass oublié</a>
                </div>
                <br/>
                <div>
                  <input type="submit" name="submit" class="bouton1" value="Connexion"/>
                </div>
              </form>
              <a href="#inscription">Tu n'as pas encore de compte?</a>
            </div>

            <div id="inscription" class="inscription col-xs-12 col-sm-6">
            <h1>Inscription</h1>
            <form id="form_inscription" method="post" action="inscription_check.php">
              
              <?php 
                if(isset($_GET['erreur'])) {
                  if($_GET['erreur'] =="inscription_pseudo") { 
                    ?> 
                      <p> <?php echo "Trop tard,".$_GET['pseudo']." est déjà pris ;-)"; ?></p>
                    <?php
                  }
                }
              ?>
              <div>
                <input type="text" class="champ" id="inputPseudo" name="pseudo" placeholder="Votre pseudo">
              </div>

              <?php
                if(isset($_GET['erreur'])) {
                  if($_GET['erreur'] == "inscription_mdp") {
                    ?>
                      <p> Ton mot de passe ne pas respecte pas les règles... J'espère que ce ne sera pas ton cas sur le terrain!</p>
                    <?php
                  }
                }
              ?>
              <div>
                <input type="password" class="champ" id="inputPass" name="pass" placeholder="*******">
              </div>

              <?php
                if(isset($_GET['erreur'])) {
                  if($_GET['erreur'] =="inscription_tel") {
                    ?>
                      <p>Oups, tu as du faire une petite erreur de saisie pour ton téléphone.</p>
                    <?php
                  }
                }
              ?>
              <div>
                <input type="tel" class="champ" id="inputTel" name="tel" placeholder="Votre numéro de telephone" pattern="^[0-9]{10}$">
              </div>

              <?php
                if(isset($_GET['erreur'])) {
                    if($_GET['erreur'] =="inscription_mail") {
                      ?>
                        <p>Oups, tu as du faire une petite erreur de saisie dans ton adresse mail.</p>
                      <?php
                    }
                  }
              ?>
              <div>
                <input type="text" class="champ" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
              </div>

              <br/>

              <input type="submit" class="bouton2" value="Inscris-toi, c'est gratuit">
            </form>
            </div>
            <hr/>
          </div>
          <hr/>

<!-- ************************************               Contenu           ********************************** -->

      <div id="div2" class="container-fluid">
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
  <!-- FOOTER -->
  <?php 
      include('footer.php');
  ?>

        	</body>
        </html>	
      <?php
}
?>