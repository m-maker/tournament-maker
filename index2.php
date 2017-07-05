<?php
  // Test de redirection 

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
        		<meta charset="utf-8">
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
<div>
          		<p>
                Sans aucun doute <strong>une révolution</strong> dans l'<strong>organisation</strong> des matchs <strong>foots en salle</strong>
              </p>
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
            <hr/>
            <h1>Tu n'as pas encore de compte?</h1>
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
              <form>

            </div>
            <br/>
            <br/>

        	</body>
        </html>	
    <?php
  }
?>