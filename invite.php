<?php 

include 'conf.php';

if (!isset($_SESSION['id']))
	header('Location: index.php');
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Invitation dans une équipe</title>
		<link rel="stylesheet" type="text/css" href="organisateur/css/orga.css">
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->
		<div class="container" id="container">

			<div class="form-invite">

                <div style="margin-bottom: 1%; margin-top: 2%;">
                    <a href="index.php" style="color: black;">
                        <span style="text-align: left; font-size: 20px; background: white; padding: 1%; border-radius: 5px;">
                            < Retour à l'accueil
                        </span>
                    </a>
                </div>
				<!-- Contenu code envoyé -->
				<?php if (isset($_GET['code_team'])){
					$code = htmlspecialchars(trim($_GET["code_team"]));
					$equipe = recupEquipeByCode($code);
					if (!empty($equipe)){ ?>

						<div class="cont-info">
							Vous avez été invité à rejoindre l'équipe <span class="bold"><?php echo $equipe['team_nom']; ?></span> qui compte <span class="bold"><?php echo compter_membres($equipe['team_id']); ?></span> joueurs.<br />
						</div>


						<form action="conf_invite?code=<?php echo $code; ?>" method="post">
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
                        <h4>(Si vous ne possédez pas ce code, demandez-le au capitaine de l'équipe que vous voulez rejoindre)</h4>
					</div>
					<form method="get">
						<input type="text" name="code_team" class="form-control" placeholder="Saisir le code d'invitation" />
						<button type="submit" class="btn btn-success btn-grand"><span class="glyphicon glyphicon-zoom-in"></span> Chercher</button>
					</form>

				<?php } ?>

			</div>
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script type="text/javascript">
		</script>

	</body>

</html>