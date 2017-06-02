<?php 

include 'conf.php';

?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<link rel="stylesheet" type="text/css" href="organisateur/css/orga.css">
		<title>Invitation dans une équipe</title>    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
    </head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">

        <h1 id="titre_corps">Invitation dans une équipe</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
		<div class="container" id="container">

			<div class="form-invite">

                <div style="margin-bottom: 1%; margin-top: 2%;">
                    <a href="index.php" style="color: black;">
                        <span style="text-align: left; font-size: 20px; background: white; padding: 1%; border-radius: 5px;">
                            < Retour à l'accueil
                        </span>
                    </a>
                </div>

                <?php if (isset($_GET['code_team'])){
                    $code = htmlspecialchars(trim($_GET["code_team"]));
                    $equipe = recupEquipeByCode($code);
                    if (!isset($_SESSION['id']) || empty($_SESSION["id"]))
                        header('Location: index.php?return=invite.php?code_team=' .$code); ?>

				<!-- Contenu code envoyé -->
				<?php
					if (!empty($equipe)){ ?>

						<div class="cont-info">
							Vous avez été invité à rejoindre l'équipe <span class="bold"><?php echo $equipe['team_nom']; ?></span> qui compte <span class="bold"><?php echo compter_membres($equipe['team_id']); ?></span> joueurs.<br />
						    Créez un compte pour vous connecter à la plateforme:
                        </div>
                        <form action="invite_check?code=<?php echo $code ?>&mail=<?php echo $_GET["mail"]; ?>" method="post">
                            <div class="row espace-bot">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-warning btn-grand" name="no">Refuser l'invitation</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-grand" name="yes">Accepter l'invitation</button>
                                </div>
                            </div>

						</form>

					<?php }else{ ?>

						<h2 class="rouge center"> Le code saisi n'est pas valide ! </h2>
						<div class="cont-info">
							Saisissez le code d'invitation d'une équipe afin de la rejoindre :
                            (Si vous ne possédez pas ce code, demandez-le au capitaine de l'équipe que vous voulez rejoindre)
						</div>
						<form method="get">
							<input type="text" name="code_team" class="form-control" placeholder="Saisir le code d'invitation" />
							<button type="submit" class="btn btn-success btn-grand"><span class="glyphicon glyphicon-zoom-in"></span> Chercher</button>
						</form>

					<?php } ?>

				<!-- -->
				<?php }else{ ?>

                    <div class="cont-info">
                        Saisissez le code d'invitation d'une équipe afin de la rejoindre :
                        (Si vous ne possédez pas ce code, demandez-le au capitaine de l'équipe que vous voulez rejoindre)
                    </div>
                    <form method="get">
                        <input type="text" name="code_team" class="form-control" placeholder="Saisir le code d'invitation" />
                        <button type="submit" class="btn btn-success btn-grand"><span class="glyphicon glyphicon-zoom-in"></span> Chercher</button>
                    </form>

				<?php } ?>

			</div>
		</div>
	</div>
</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script type="text/javascript">
		</script>

	</body>

</html>